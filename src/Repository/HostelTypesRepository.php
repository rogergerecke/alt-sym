<?php

namespace App\Repository;

use App\Entity\HostelTypes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HostelTypes|null find($id, $lockMode = null, $lockVersion = null)
 * @method HostelTypes|null findOneBy(array $criteria, array $orderBy = null)
 * @method HostelTypes[]    findAll()
 * @method HostelTypes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HostelTypesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HostelTypes::class);
    }

    /**
     * Return a array to build the form choices $key-$value
     * @return null |array
     */
    public function getHostelTypesForForm(){

        // get all regions there are active
        $result = $this->createQueryBuilder('ht')
            ->select('ht.name','ht.type_id')
            ->where('ht.active = 1')
            ->orderBy('ht.sort', 'ASC')
            ->getQuery()
            ->getResult()
        ;

        // build associative array doctrine indexBy fault
        $res = null;
        foreach ($result as $value){
            $res[$value['name']] = $value['type_id'];
        }

        return $res;
    }
    // /**
    //  * @return HostelTypes[] Returns an array of HostelTypes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HostelTypes
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
