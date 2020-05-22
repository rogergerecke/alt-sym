<?php

namespace App\Repository;

use App\Entity\Hostel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Hostel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hostel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hostel[]    findAll()
 * @method Hostel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HostelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hostel::class);
    }

    public function findHostelsWithFilter(?array $filter)
    {
        // first idee
       /* [regions] => 61 [hostel_types] => 4 [quantity_person] => 1 [submit] => [price_range] => 10;80 [see_distance] => 1;5 )*/
        $qb = $this->createQueryBuilder('h');

        if ($filter['regions']) {
            $qb
                ->andWhere('h.room_types LIKE :filter OR h.partner_id LIKE :filter')
                ->setParameter('filter', '%'.$filter['regions'].'%');
        }

        if ($filter['hostel_types']) {
            $qb
                ->andWhere('h.room_types LIKE :filter OR h.partner_id LIKE :filter')
                ->setParameter('filter', '%'.$filter['hostel_types'].'%');
        }

        return $qb
            ->orderBy('h.status', 'DESC')
            ->getQuery()
            ->getResult();
    }


    /**
     * Find all the hostels for the Start Page Listing
     * with
     *
     * @return int|mixed|string
     */
    public function findStartPageHostels()
    {
        $qb = $this->createQueryBuilder('hsp')
            ->where('hsp.status = 1')
            ->andWhere('hsp.startpage = 1')
            ->andWhere('hsp.top_placement_finished >= :time')
            ->setParameter('time', new \DateTime('now'))
            ->addOrderBy('hsp.sort','DESC');

        return $qb->getQuery()->getResult();

    }

    /**
     * Find the hostels for the Top Listing in Hostel View Controller
     * @return int|mixed|string
     */
    public function findTopListingHostels()
    {
        $qb = $this->createQueryBuilder('tlh')
            ->where('tlh.status = 1')
            ->andWhere('tlh.toplisting = 1')
            ->andWhere('tlh.top_placement_finished >= :time')
            ->setParameter('time', new \DateTime('now'))
            ->addOrderBy('tlh.sort','DESC');

        return $qb->getQuery()->getResult();

    }

    // /**
    //  * @return Hostel[] Returns an array of Hostel objects
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
    public function findOneBySomeField($value): ?Hostel
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
