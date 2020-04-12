<?php

namespace App\Repository;

use App\Entity\OpenWeather;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OpenWeather|null find($id, $lockMode = null, $lockVersion = null)
 * @method OpenWeather|null findOneBy(array $criteria, array $orderBy = null)
 * @method OpenWeather[]    findAll()
 * @method OpenWeather[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OpenWeatherRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OpenWeather::class);
    }

    // /**
    //  * @return OpenWeather[] Returns an array of OpenWeather objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OpenWeather
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
