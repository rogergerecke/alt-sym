<?php

namespace App\Entity;

use App\Repository\StatisticsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StatisticsRepository::class)
 */
class Statistics
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $global_page_view;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $page_view;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $notice_hostel;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $hostel_id;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGlobalPageView(): ?int
    {
        return $this->global_page_view;
    }

    public function setGlobalPageView(?int $global_page_view): self
    {
        $this->global_page_view = $global_page_view;

        return $this;
    }

    public function getPageView(): ?int
    {
        return $this->page_view;
    }

    public function setPageView(?int $page_view): self
    {
        $this->page_view = $page_view;

        return $this;
    }

    public function getNoticeHostel(): ?int
    {
        return $this->notice_hostel;
    }

    public function setNoticeHostel(?int $notice_hostel): self
    {
        $this->notice_hostel = $notice_hostel;

        return $this;
    }

    public function getHostelId(): ?int
    {
        return $this->hostel_id;
    }

    public function setHostelId(?int $hostel_id): self
    {
        $this->hostel_id = $hostel_id;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
