<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This class contain the id of Gallery
 *
 * @ORM\Entity(repositoryClass="App\Repository\MediaGalleryRepository")
 */
class MediaGallery
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * The id of the owen user from the gallery
     *
     * @ORM\Column(type="integer")
     */
    private $user_id;

    /**
     * Sort gallery's it is more than one gallery
     *
     * @ORM\Column(type="integer")
     */
    private $sort;

    /**
     * The class var describe the display view point
     * top_position, hostel_detail, index, content
     *
     * @ORM\Column(type="string", length=32)
     */
    private $class;

    /**
     * On or offline
     *
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * The hostel_id
     *
     * @ORM\Column(type="integer")
     */
    private $hostel_id;

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

    public function getSort(): ?int
    {
        return $this->sort;
    }

    public function setSort(int $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    public function getClass(): ?string
    {
        return $this->class;
    }

    public function setClass(string $class): self
    {
        $this->class = $class;

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

    public function getHostelId(): ?int
    {
        return $this->hostel_id;
    }

    public function setHostelId(int $hostel_id): self
    {
        $this->hostel_id = $hostel_id;

        return $this;
    }
}
