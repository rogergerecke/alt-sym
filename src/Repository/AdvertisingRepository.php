<?php

namespace App\Repository;

use App\Entity\Advertising;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Advertising|null find($id, $lockMode = null, $lockVersion = null)
 * @method Advertising|null findOneBy(array $criteria, array $orderBy = null)
 * @method Advertising[]    findAll()
 * @method Advertising[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdvertisingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Advertising::class);
    }


    /**
     * Get the Adverting with the Hostel data
     * only if its status is online and no end_date_advertising
     * @return int|mixed|string
     */
    public function getAdvertising()
    {
        $now = new \DateTime();

        $qb = $this->createQueryBuilder('a')
            ->andWhere('a.status = 1')
            ->andWhere('a.start_date_advertising <= :date')
            ->andWhere('a.end_date_advertising >= :date')
            ->setParameter('date', $now->format('Y-m-d h:i:s'));

        return $qb->getQuery()->getResult();
    }
    // /**
    //  * @return Advertising[] Returns an array of Advertising objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Advertising
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
