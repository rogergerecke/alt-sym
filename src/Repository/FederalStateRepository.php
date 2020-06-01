<?php

namespace App\Repository;

use App\Entity\FederalState;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FederalState|null find($id, $lockMode = null, $lockVersion = null)
 * @method FederalState|null findOneBy(array $criteria, array $orderBy = null)
 * @method FederalState[]    findAll()
 * @method FederalState[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FederalStateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FederalState::class);
    }

    // /**
    //  * @return FederalState[] Returns an array of FederalState objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FederalState
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
