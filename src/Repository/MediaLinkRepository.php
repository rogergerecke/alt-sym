<?php

namespace App\Repository;

use App\Entity\MediaLink;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MediaLink|null find($id, $lockMode = null, $lockVersion = null)
 * @method MediaLink|null findOneBy(array $criteria, array $orderBy = null)
 * @method MediaLink[]    findAll()
 * @method MediaLink[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaLinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MediaLink::class);
    }

    // /**
    //  * @return MediaLink[] Returns an array of MediaLink objects
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
    public function findOneBySomeField($value): ?MediaLink
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
