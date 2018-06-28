<?php

namespace App\Repository;

use App\Entity\Test;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Test|null find($id, $lockMode = null, $lockVersion = null)
 * @method Test|null findOneBy(array $criteria, array $orderBy = null)
 * @method Test[]    findAll()
 * @method Test[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Test::class);
    }

    public function findRootTests()
    {
        return $this->createQueryBuilder('t')
            ->where("t.folder is null")
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

    public function findTest($username, $name)
    {
        return $this->createQueryBuilder('t')
            ->select('t.id', 't.random')
            ->where("t.username = :username")
            ->andWhere("t.name = :name")
            ->setParameter('username', $username)
            ->setParameter('name', $name)
            ->getQuery()
            ->getResult()
            ;
    }

    /*
    public function findOneBySomeField($value): ?Test
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
