<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HostelRoomsRepository")
 */
class HostelRooms
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
     */
    private $hostel_room;

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

    public function getHostelRoom(): ?int
    {
        return $this->hostel_room;
    }

    public function setHostelRoom(int $hostel_room): self
    {
        $this->hostel_room = $hostel_room;

        return $this;
    }
}
