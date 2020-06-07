<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * Hostel contain the full address data for hostel location a user cant have many hostels.
 * @ORM\Entity(repositoryClass="App\Repository\HostelRepository")
 */
class Hostel
{


    /**
     * Many hostels have one user. This is the owning side.
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="hostels")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;


    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     * @Assert\Type(type="integer")
     */
    private $user_id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Type(type="string")
     */
    private $hostel_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address_sub;

    /**
     * @ORM\Column(type="integer")
     */
    private $postcode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $state;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $country;

    /**
     * Country ISO id code number
     * @ORM\Column(type="integer", nullable=true)
     */
    private $country_id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $longitude;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $latitude;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fax;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $web;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * ex. EUR
     *
     * @ORM\Column(type="string", length=3, nullable=true)
     */
    private $currency;

    /**
     *
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $room_types;

    /**
     * The amenities contain a json array
     *
     * ['camping', 'vila', 'penthouse']
     *
     * @ORM\Column(type="json", nullable=true)
     */
    private $amenities = [];

    /**
     * The html description of the hotel
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $api_key;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $hostel_availability_url;

    /**
     * @ORM\Column(type="smallint")
     */
    private $sort;

    /**
     * @ORM\Column(type="boolean")
     */
    private $startpage;

    /**
     * @ORM\Column(type="boolean")
     */
    private $toplisting;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $top_placement_finished;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mobile;

    /**
     * @ORM\Column(type="float")
     */
    private $distance_to_see;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $hostel_type;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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

    public function getHostelName(): ?string
    {
        return $this->hostel_name;
    }

    public function setHostelName(string $hostel_name): self
    {
        $this->hostel_name = $hostel_name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getAddressSub(): ?string
    {
        return $this->address_sub;
    }

    public function setAddressSub(?string $address_sub): self
    {
        $this->address_sub = $address_sub;

        return $this;
    }

    public function getPostcode(): ?int
    {
        return $this->postcode;
    }

    public function setPostcode(int $postcode): self
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCountryId(): ?int
    {
        return $this->country_id;
    }

    public function setCountryId(?int $country_id): self
    {
        $this->country_id = $country_id;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getFax(): ?string
    {
        return $this->fax;
    }

    public function setFax(?string $fax): self
    {
        $this->fax = $fax;

        return $this;
    }

    public function getWeb(): ?string
    {
        return $this->web;
    }

    public function setWeb(?string $web): self
    {
        $this->web = $web;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(?string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getRoomTypes(): ?string
    {
        return $this->room_types;
    }

    public function setRoomTypes(string $room_types): self
    {
        $this->room_types = $room_types;

        return $this;
    }

    public function getAmenities(): ?array
    {
        return $this->amenities;
    }

    public function setAmenities(?array $amenities): self
    {
        $this->amenities = $amenities;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getApiKey(): ?string
    {
        return $this->api_key;
    }

    public function setApiKey(?string $api_key): self
    {
        $this->api_key = $api_key;

        return $this;
    }

    public function getHostelAvailabilityUrl(): ?string
    {
        return $this->hostel_availability_url;
    }

    public function setHostelAvailabilityUrl(?string $hostel_availability_url): self
    {
        $this->hostel_availability_url = $hostel_availability_url;

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

    public function getStartpage(): ?bool
    {
        return $this->startpage;
    }

    public function setStartpage(bool $startpage): self
    {
        $this->startpage = $startpage;

        return $this;
    }

    public function getToplisting(): ?bool
    {
        return $this->toplisting;
    }

    public function setToplisting(bool $toplisting): self
    {
        $this->toplisting = $toplisting;

        return $this;
    }

    public function getTopPlacementFinished(): ?\DateTimeInterface
    {
        return $this->top_placement_finished;
    }

    public function setTopPlacementFinished(?\DateTimeInterface $top_placement_finished): self
    {
        $this->top_placement_finished = $top_placement_finished;

        return $this;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(?string $mobile): self
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function getDistanceToSee(): ?float
    {
        return $this->distance_to_see;
    }

    public function setDistanceToSee(float $distance_to_see): self
    {
        $this->distance_to_see = $distance_to_see;

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

    public function getHostelType(): ?string
    {
        return $this->hostel_type;
    }

    public function setHostelType(?string $hostel_type): self
    {
        $this->hostel_type = $hostel_type;

        return $this;
    }
}
