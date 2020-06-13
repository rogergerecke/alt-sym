<?php

namespace App\Entity;

use App\Repository\AdvertisingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdvertisingRepository::class)
 */
class Advertising
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $user_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $text;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $link;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $start_date_advertising;

    /**
     * @ORM\Column(type="datetime")
     */
    private $end_date_advertising;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isUserMadeChanges;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getStartDateAdvertising(): ?\DateTimeInterface
    {
        return $this->start_date_advertising;
    }

    public function setStartDateAdvertising(\DateTimeInterface $start_date_advertising): self
    {
        $this->start_date_advertising = $start_date_advertising;

        return $this;
    }

    public function getEndDateAdvertising(): ?\DateTimeInterface
    {
        return $this->end_date_advertising;
    }

    public function setEndDateAdvertising(\DateTimeInterface $end_date_advertising): self
    {
        $this->end_date_advertising = $end_date_advertising;

        return $this;
    }

    public function getIsUserMadeChanges(): ?bool
    {
        return $this->isUserMadeChanges;
    }

    public function setIsUserMadeChanges(?bool $isUserMadeChanges): self
    {
        $this->isUserMadeChanges = $isUserMadeChanges;

        return $this;
    }

}
