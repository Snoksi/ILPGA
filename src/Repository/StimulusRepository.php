<?php

namespace App\Repository;

use App\Entity\Stimulus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Stimulus|null find($id, $lockMode = null, $lockVersion = null)
 * @method Stimulus|null findOneBy(array $criteria, array $orderBy = null)
 * @method Stimulus[]    findAll()
 * @method Stimulus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StimulusRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Stimulus::class);
    }

//    /**
//     * @return Stimulus[] Returns an array of Stimulus objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Stimulus
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findStimulus($idTest)
    {
        return $this->createQueryBuilder('t')
            ->select('t.source', 't.name', 't.playCount')
            ->where("t.page = :idTest")
            ->setParameter('idTest', $idTest)
            ->getQuery()
            ->getResult()
            ;
    }
}
