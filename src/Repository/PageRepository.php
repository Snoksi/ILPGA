<?php
/**
 * Created by PhpStorm.
 * User: saidi
 * Date: 6/20/2018
 * Time: 12:23 PM
 */

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Entity\Page;

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

//    /**
//     * @return Question[] Returns an array of Question objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Question
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


    public function findPages($idTest = null, $profil = null)
    {
        $qb = $this->createQueryBuilder('u')
            ->select('u.id', 'u.title')
            ->leftJoin('App:Response', 'q', Join::WITH, 'q.profil = :profil')
            ->where('u.test = :id')
            ->setParameter('id', $idTest)
            ->andWhere('q.page IS NULL')
            ->setParameter('profil', $profil)
            ->getQuery()
            ->getResult();
        return $qb;
    }

    public function findPagesSql($id = null, $profil = null)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p.id
        FROM \App\Entity\Page p
        INNER JOIN \App\Entity\Response q ON q.page = p.id;
        WHERE p.test = :id AND q.profil = :profil'
        )->setParameter('id', $id)->setParameter('profil', $profil);

        // returns an array of Product objects
        return $query->execute();
    }

    public function findPageBasic($idTest = null, $profil = null)
    {
        $qb = $this->createQueryBuilder('u')
            ->select('u.id', 'u.title')
            ->innerJoin('App:Response', 'q',  'WITH',  'q.page <> u.id')
            ->where('u.test = :id')
            ->andWhere('q.profil = :profil')
            ->setParameter('id', $idTest)
            ->setParameter('profil', $profil)
            ->getQuery()
            ->getResult()
        ;
        return $qb;
    }
}