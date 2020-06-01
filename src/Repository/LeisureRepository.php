<?php

namespace App\Repository;

use App\Entity\Leisure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Leisure|null find($id, $lockMode = null, $lockVersion = null)
 * @method Leisure|null findOneBy(array $criteria, array $orderBy = null)
 * @method Leisure[]    findAll()
 * @method Leisure[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LeisureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Leisure::class);
    }

    // /**
    //  * @return Leisure[] Returns an array of Leisure objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Leisure
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
