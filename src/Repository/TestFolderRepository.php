<?php

namespace App\Repository;

use App\Entity\TestFolder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TestFolder|null find($id, $lockMode = null, $lockVersion = null)
 * @method TestFolder|null findOneBy(array $criteria, array $orderBy = null)
 * @method TestFolder[]    findAll()
 * @method TestFolder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestFolderRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TestFolder::class);
    }

    public function findRootFolders()
    {
        return $this->createQueryBuilder('t')
            ->where("t.parent is null")
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param null $query
     * @return mixed
     */
    public function findByName($query = null)
    {
        return $this->createQueryBuilder('t')
            ->where("t.name LIKE :query")
            ->setParameter('query', "%".$query."%")
            ->getQuery()
            ->getResult()
            ;
    }
}
