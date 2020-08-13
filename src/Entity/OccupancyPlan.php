<?php

namespace App\Entity;

use App\Repository\OccupancyPlanRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OccupancyPlanRepository::class)
 */
class OccupancyPlan
{
    /**
     * A hostel can have many rooms
     *
     * @ORM\ManyToOne(targetEntity=Hostel::class, inversedBy="occupancy")
     * @ORM\JoinColumn(name="hostel_id", referencedColumnName="id")
     */
    private $hostel;

    /**
     * @return mixed
     */
    public function getHostel()
    {
        return $this->hostel;
    }

    /**
     * @param mixed $hostel
     * @return OccupancyPlan
     */
    public function setHostel($hostel)
    {
        $this->hostel = $hostel;

        return $this;
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $hostel_id;

    /**
     * @ORM\Column(type="date")
     */
    private $date_from;

    /**
     * @ORM\Column(type="date")
     */
    private $date_to;

    /**
     * @ORM\Column(type="smallint")
     */
    private $utilization;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHostelId(): ?int
    {
        return $this->hostel_id;
    }

    public function setHostelId(int $hostel_id): self
    {
        $this->hostel_id = $hostel_id;

        return $this;
    }

    public function getDateFrom(): ?\DateTimeInterface
    {
        return $this->date_from;
    }

    public function setDateFrom(\DateTimeInterface $date_from): self
    {
        $this->date_from = $date_from;

        return $this;
    }

    public function getDateTo(): ?\DateTimeInterface
    {
        return $this->date_to;
    }

    public function setDateTo(\DateTimeInterface $date_to): self
    {
        $this->date_to = $date_to;

        return $this;
    }

    public function getUtilization(): ?int
    {
        return $this->utilization;
    }

    public function setUtilization(int $utilization): self
    {
        $this->utilization = $utilization;

        return $this;
    }
}
