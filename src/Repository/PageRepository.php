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

    public function getInfosPage($idTest)
    {
        $qb = $this->createQueryBuilder('page')
            ->select('page.type', 'page.title','page.content')
            ->where('page.id = :id')
            ->setParameter('id', $idTest)
            ->getQuery()
            ->getResult()
        ;
        return $qb;
    }

    public function findPagesSql($idTest, $profil): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT page.id 
            FROM App\Entity\Page page
            LEFT JOIN App\Entity\Response response WITH (page.id = response.page AND response.profil = :profil) 
            WHERE response.page IS NULL AND page.test = :idTest'
        )->setParameter('idTest', $idTest)
            ->setParameter('profil', $profil);

        // returns an array of Product objects
        return $query->execute();
    }
}
