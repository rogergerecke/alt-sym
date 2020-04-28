<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MediaToMediaGalleryRepository")
 */
class MediaToMediaGallery
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
    private $media_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $media_gallery_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMediaId(): ?int
    {
        return $this->media_id;
    }

    public function setMediaId(int $media_id): self
    {
        $this->media_id = $media_id;

        return $this;
    }

    public function getMediaGalleryId(): ?int
    {
        return $this->media_gallery_id;
    }

    public function setMediaGalleryId(int $media_gallery_id): self
    {
        $this->media_gallery_id = $media_gallery_id;

        return $this;
    }
}
