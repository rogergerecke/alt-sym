<?php

namespace App\Repository;

use App\Entity\UserPrivilegesTypes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserPrivilegesTypes|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserPrivilegesTypes|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserPrivilegesTypes[]    findAll()
 * @method UserPrivilegesTypes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserPrivilegesTypesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPrivilegesTypes::class);
    }

    // /**
    //  * @return UserPrivilegesTypes[] Returns an array of UserPrivilegesTypes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserPrivilegesTypes
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
