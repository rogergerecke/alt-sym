<?php

namespace App\Repository;

use App\Entity\RoomAmenities;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RoomAmenities|null find($id, $lockMode = null, $lockVersion = null)
 * @method RoomAmenities|null findOneBy(array $criteria, array $orderBy = null)
 * @method RoomAmenities[]    findAll()
 * @method RoomAmenities[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomAmenitiesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RoomAmenities::class);
    }

    // /**
    //  * @return RoomAmenities[] Returns an array of RoomAmenities objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RoomAmenities
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
