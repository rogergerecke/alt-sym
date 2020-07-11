<?php

namespace App\Repository;

use App\Entity\HostelGallery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HostelGallery|null find($id, $lockMode = null, $lockVersion = null)
 * @method HostelGallery|null findOneBy(array $criteria, array $orderBy = null)
 * @method HostelGallery[]    findAll()
 * @method HostelGallery[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HostelGalleryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HostelGallery::class);
    }

    // /**
    //  * @return TestGallery[] Returns an array of TestGallery objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TestGallery
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
