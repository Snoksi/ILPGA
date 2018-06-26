<?php

namespace App\EventListener;

use App\Entity\Block;
use App\Entity\Page;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Psr\Log\LoggerInterface;


class PositionListener
{

    private $em;

    private $log;

    public function __construct(LoggerInterface $logger)
    {
        $this->log = $logger;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $this->em = $args->getEntityManager();

        // Doit être à la racine du test
        if(!$entity instanceof Page && !$entity instanceof Block) return false;
        // Si la page appartient à un block, alors on arrête.
        if($entity instanceof Page && $entity->getBlock() != null) return false;

        $stmt = $this->em->getConnection()->prepare(
            '
            SELECT MAX(max) as position
                FROM 
                (
                    SELECT MAX(position) as max 
                    FROM page
                    WHERE block_id IS NULL
                    AND test_id = :test_id
                    UNION
                    SELECT MAX(position) as max
                    FROM block
                    WHERE test_id = :test_id
                ) AS e
        ');

        $stmt->execute(['test_id' => $entity->getTest()->getId()]);
        $row = $stmt->fetch();

        $entity->setPosition((is_null($row['position'])) ? 1 : $row['position'] + 1);
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $this->em = $args->getEntityManager();

        // Doit être une page dans un bloc de stimuli
        if(!$entity instanceof Page) return false;
        if($entity->getBlock() == null) return false;

        $this->log->info('Block_id : '.$entity->getBlock()->getId());

        $stmt = $this->em->getConnection()->prepare(
            '
                UPDATE page 
                SET position = 
                (
                    SELECT IFNULL(MAX(position), 0) + 1
                    FROM (SELECT * FROM page) as p 
                    WHERE p.block_id = :block_id
                )
                WHERE id = :id
            ');

        $stmt->execute([
            'block_id' => $entity->getBlock()->getId(),
            'id' => $entity->getId()
        ]);
    }
}