<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This class hold all media names image.jpg, audio.mp3 and the owner
 * of the file example hostel_id = 8
 *
 * @ORM\Entity(repositoryClass="App\Repository\MediaRepository")
 */
class Media
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * The file name file.jpg
     *
     * @ORM\Column(type="string", length=255)
     */
    private $file;

    /**
     * The type image, video, url
     *
     * @ORM\Column(type="string", length=10)
     */
    private $type;

    /**
     * The class var describe the display view point
     * top_position, hostel_detail, index, content
     *
     * @ORM\Column(type="string", length=32)
     */
    private $class;


    /**
     * Status on or offline
     *
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * The owner id of the media file
     *
     * @ORM\Column(type="integer")
     */
    private $user_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }
}
