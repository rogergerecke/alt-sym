<?php

namespace App\Repository;

use App\Entity\RoomAmenitiesDescription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RoomAmenitiesDescription|null find($id, $lockMode = null, $lockVersion = null)
 * @method RoomAmenitiesDescription|null findOneBy(array $criteria, array $orderBy = null)
 * @method RoomAmenitiesDescription[]    findAll()
 * @method RoomAmenitiesDescription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomAmenitiesDescriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RoomAmenitiesDescription::class);
    }

    // /**
    //  * @return RoomAmenitiesDescription[] Returns an array of RoomAmenitiesDescription objects
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
    public function findOneBySomeField($value): ?RoomAmenitiesDescription
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
