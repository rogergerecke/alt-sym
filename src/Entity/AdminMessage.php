<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AdminMessageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class AdminMessage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $heading;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sub_heading;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $message;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_add;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHeading(): ?string
    {
        return $this->heading;
    }

    public function setHeading(string $heading): self
    {
        $this->heading = $heading;

        return $this;
    }

    public function getSubHeading(): ?string
    {
        return $this->sub_heading;
    }

    public function setSubHeading(?string $sub_heading): self
    {
        $this->sub_heading = $sub_heading;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

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

    public function getDateAdd(): ?\DateTimeInterface
    {
        return $this->date_add;
    }

    public function setDateAdd(\DateTimeInterface $date_add): self
    {
        $this->date_add = $date_add;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function setDateAddDefaultValue()
    {
        $this->date_add = new \DateTime();
    }
}
