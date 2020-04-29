<?php

namespace App\Repository;

use App\Entity\MediaGalleryDescripton;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MediaGalleryDescripton|null find($id, $lockMode = null, $lockVersion = null)
 * @method MediaGalleryDescripton|null findOneBy(array $criteria, array $orderBy = null)
 * @method MediaGalleryDescripton[]    findAll()
 * @method MediaGalleryDescripton[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaGalleryDescriptonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MediaGalleryDescripton::class);
    }

    // /**
    //  * @return MediaGalleryDescripton[] Returns an array of MediaGalleryDescripton objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MediaGalleryDescripton
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
