<?php

namespace App\Repository;

use App\Entity\Page;
use App\Entity\Test;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Page|null find($id, $lockMode = null, $lockVersion = null)
 * @method Page|null findOneBy(array $criteria, array $orderBy = null)
 * @method Page[]    findAll()
 * @method Page[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Page::class);
    }

    public function getPagesAndBlocks(Test $test)
    {
        $sql = 'SELECT * FROM (
            SELECT id, title, position, "page" AS type 
            FROM page
            WHERE test_id = :test_id
            AND type <> "question"
            UNION
            SELECT id, title, position, "block" AS type
            FROM block
            WHERE test_id = :test_id
            ) AS u
            ORDER BY u.position
        ';

        return $this->getEntityManager()->getConnection()
            ->executeQuery($sql, [
               'test_id' => $test->getId()
            ])
            ->fetchAll();
    }

    public function updatePosition($type, $id, $position)
    {
        $sql = 'UPDATE page, block
        SET
        
        WHERE '.$type.' = :id
        ';

        return $this->getEntityManager()->getConnection()
            ->executeQuery($sql, [
                'test_id' => $test->getId()
            ]);
    }
}
