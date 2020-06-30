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
    private $regions_name;


    /**
     * @var RegionsRepository
     */
    private $regionsRepository;

    public function __construct(ManagerRegistry $registry, RegionsRepository $regionsRepository)
    {
        parent::__construct($registry, Hostel::class);
        $this->regionsRepository = $regionsRepository;
    }


    /**
     * The complex hostel search function
     * @param array|null $filter
     * @return int|mixed|string
     * @throws \Exception
     */
    public function findHostelsWithFilter(?array $filter)
    {
        // first idee
        /* [regions] => 61 [hostel_types] => 4 [quantity_person] => 1 [submit] => [price_range] => 10;80 [see_distance] => 1;5 )*/
        $qb = $this->createQueryBuilder('h');

        // add the regions filter works by postcode
        if ($filter['regions']) {
            $plz = $this->regionsRepository->findOneBy(['regions_id' => $filter['regions']]);

            if ($plz) {
                // set the regions name for heading the search page
                $this->setRegionsName($plz->getName());

                $qb
                    ->andWhere('h.postcode = :plz')
                    ->setParameter('plz', $plz->getZipcode());
            } else {
                throw new \Exception('This region doesnt exist check your regions table.');
            }
        }

        // add the hostel type filter ['hostel_types'] => 64
        if ($filter['hostel_types']) {
            $qb
                ->leftJoin(
                    'App\Entity\AmenitiesTypes',
                    'at',
                    'WITH',
                    'h.hostel_type = at.name'
                )
                ->andWhere('at.amenities_id = :id')
                ->setParameter('id', $filter['hostel_types']);
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
     * @param int $limit
     * @return int|mixed|string
     */
    public function findStartPageHostels(int $limit = 3)
    {
        $qb = $this->createQueryBuilder('hsp')
            ->where('hsp.status = 1')
            ->andWhere('hsp.startpage = 1')
            ->andWhere('hsp.top_placement_finished >= :time')
            ->setParameter('time', new \DateTime('now'))
            ->addOrderBy('hsp.sort', 'DESC')
            ->setMaxResults($limit);

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
            ->addOrderBy('tlh.sort', 'DESC');

        return $qb->getQuery()->getResult();
    }


    /**
     * Find all hostel with the $id_array of hostel ids
     *
     * @param array $id_array
     * @return int|mixed|string
     */
    public function findAllHostelWithId(array $id_array)
    {

        $qb = $this->createQueryBuilder('hn');

        $i = 1;
        foreach ($id_array as $id) {
            if ($i == 1) {
                $qb
                    ->where("hn.id = :id$i")
                    ->setParameter("id$i", $id);
            } else {
                $qb
                    ->orWhere("hn.id = :id$i")
                    ->setParameter("id$i", $id);
            }

            $i++;
        }

        $qb->andWhere('hn.status = 1');

        return $qb
            ->getQuery()
            ->getResult();
    }

    /**
     * @return mixed
     */
    public function getRegionsName()
    {
        return $this->regions_name;
    }

    /**
     * @param mixed $regions_name
     * @return HostelRepository
     */
    public function setRegionsName($regions_name)
    {
        $this->regions_name = $regions_name;

        return $this;
    }
}
