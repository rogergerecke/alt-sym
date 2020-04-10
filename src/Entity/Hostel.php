<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * @ORM\Entity(repositoryClass="App\Repository\HostelRepository")
 */
class Hostel
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
    private $hostel_id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     * @Assert\Type(type="string")
     */
    private $customer_id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Type(type="string")
     */
    private $hostel_name;

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

    public function getCustomerId(): ?int
    {
        return $this->customer_id;
    }

    public function setCustomerId(int $customer_id): self
    {
        $this->customer_id = $customer_id;

        return $this;
    }

    public function getHostelName(): ?string
    {
        return $this->hostel_name;
    }

    public function setHostelName(string $hostel_name): self
    {
        $this->hostel_name = $hostel_name;

        return $this;
    }
}
