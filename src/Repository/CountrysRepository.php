<?php

namespace App\Repository;

use App\Entity\Countrys;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Countrys|null find($id, $lockMode = null, $lockVersion = null)
 * @method Countrys|null findOneBy(array $criteria, array $orderBy = null)
 * @method Countrys[]    findAll()
 * @method Countrys[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CountrysRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Countrys::class);
    }

    // /**
    //  * @return Countrys[] Returns an array of Countrys objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Countrys
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
