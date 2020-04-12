<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\OpenWeatherRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class OpenWeather
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     */
    private $data_type;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $weather_data = [];

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="integer")
     */
    private $import_status_code;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDataType(): ?string
    {
        return $this->data_type;
    }

    public function setDataType(string $data_type): self
    {
        $this->data_type = $data_type;

        return $this;
    }

    public function getWeatherData(): ?array
    {
        return $this->weather_data;
    }

    public function setWeatherData(?array $weather_data): self
    {
        $this->weather_data = $weather_data;

        return $this;
    }

    public function getImportStatusCode(): ?int
    {
        return $this->import_status_code;
    }

    public function setImportStatusCode(int $import_status_code): self
    {
        $this->import_status_code = $import_status_code;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function setDateDefaultValue()
    {
        $this->date = new \DateTime();
    }
}
