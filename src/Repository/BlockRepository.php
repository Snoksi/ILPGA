<?php

namespace App\Repository;

use App\Entity\PageGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PageGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method PageGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method PageGroup[]    findAll()
 * @method PageGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlockRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PageGroup::class);
    }

//    /**
//     * @return PageGroup[] Returns an array of PageGroup objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PageGroup
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findIdBlock($id_test)
    {
        return $this->createQueryBuilder('t')
            ->select('t.id')
            ->where("t.test = :test_id")
            ->setParameter('test_id', $id_test)
            ->getQuery()
            ->getResult()
            ;
    }
}
