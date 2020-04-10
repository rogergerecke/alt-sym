<?php

namespace App\Repository;

use App\Entity\StaticSite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method StaticSite|null find($id, $lockMode = null, $lockVersion = null)
 * @method StaticSite|null findOneBy(array $criteria, array $orderBy = null)
 * @method StaticSite[]    findAll()
 * @method StaticSite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StaticSiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StaticSite::class);
    }

    // /**
    //  * @return StaticSite[] Returns an array of StaticSite objects
    //  */
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
    public function findOneBySomeField($value): ?StaticSite
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
