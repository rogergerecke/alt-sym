<?php

namespace App\Repository;

use App\Entity\ImageType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ImageType|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImageType|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImageType[]    findAll()
 * @method ImageType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImageType::class);
    }

    // /**
    //  * @return ImageType[] Returns an array of ImageType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ImageType
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
