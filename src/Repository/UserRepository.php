<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getPage($page = 1)
    {
        $qb = $this->createQueryBuilder('u')
            ->setFirstResult(20 * ($page - 1))
            ->setMaxResults(20)
        ;

        return new Paginator($qb);
    }

    public function search($query = null, $page = 1)
    {
        $qb = $this->createQueryBuilder('u')
            ->where('u.username LIKE :query')
            ->orWhere('u.email LIKE :query')
            ->orWhere('u.firstname LIKE :query')
            ->orWhere('u.lastname LIKE :query')
            ->setParameter('query', '%'.$query.'%')
            ->setFirstResult(20 * ($page - 1))
            ->setMaxResults(20)
        ;

        return new Paginator($qb);
    }
}
