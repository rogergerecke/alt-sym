<?php

namespace App\Repository;

use App\Entity\RoomTypes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RoomTypes|null find($id, $lockMode = null, $lockVersion = null)
 * @method RoomTypes|null findOneBy(array $criteria, array $orderBy = null)
 * @method RoomTypes[]    findAll()
 * @method RoomTypes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomTypesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RoomTypes::class);
    }

    // /**
    //  * @return RoomTypes[] Returns an array of RoomTypes objects
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
    public function findOneBySomeField($value): ?RoomTypes
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
