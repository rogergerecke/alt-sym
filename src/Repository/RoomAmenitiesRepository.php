<?php

namespace App\Repository;

use App\Entity\RoomAmenities;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RoomAmenities|null find($id, $lockMode = null, $lockVersion = null)
 * @method RoomAmenities|null findOneBy(array $criteria, array $orderBy = null)
 * @method RoomAmenities[]    findAll()
 * @method RoomAmenities[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomAmenitiesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RoomAmenities::class);
    }


    /**
     * Get the full Room Amenities option with description selected by
     * $lang code
     *
     * @param string $_lang
     * @return int|mixed|string
     */
    public function getRoomAmenitiesWithDescription($_lang = 'de')
    {

        $em = $this->getEntityManager();

        $query = $em->createQuery(
            'SELECT r.id,r.name,r.icon, d 
             FROM App\Entity\RoomAmenities r
          LEFT OUTER JOIN App\Entity\RoomAmenitiesDescription d WITH 
              r.id = d.ra_id
             AND r.status = 1
             AND d.lang = :lang'
        )->setParameter('lang',$_lang);

        return $query->getResult();
    }

    // /**
    //  * @return RoomAmenities[] Returns an array of RoomAmenities objects
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
    public function findOneBySomeField($value): ?RoomAmenities
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
