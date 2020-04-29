<?php

namespace App\Repository;

use App\Entity\AmenitiesTypes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AmenitiesTypes|null find($id, $lockMode = null, $lockVersion = null)
 * @method AmenitiesTypes|null findOneBy(array $criteria, array $orderBy = null)
 * @method AmenitiesTypes[]    findAll()
 * @method AmenitiesTypes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AmenitiesTypesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AmenitiesTypes::class);
    }

    public function getAmenitiesTypesForForm(){

        // get all regions there are active
        $result = $this->createQueryBuilder('at')
            ->select('at.name','at.amenities_id')
            ->where('at.status = 1')
            ->orderBy('at.sort', 'ASC')
            ->getQuery()
            ->getResult()
        ;

        // build associative array doctrine indexBy fault
        $res = null;
        foreach ($result as $value){
            $res[$value['name']] = $value['amenities_id'];
        }

        return $res;
    }

    // /**
    //  * @return AmenitiesTypes[] Returns an array of AmenitiesTypes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AmenitiesTypes
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
