<?php

namespace App\Repository;

use App\Entity\HostelRooms;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HostelRooms|null find($id, $lockMode = null, $lockVersion = null)
 * @method HostelRooms|null findOneBy(array $criteria, array $orderBy = null)
 * @method HostelRooms[]    findAll()
 * @method HostelRooms[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HostelRoomsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HostelRooms::class);
    }

    // /**
    //  * @return HostelRooms[] Returns an array of HostelRooms objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HostelRooms
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
