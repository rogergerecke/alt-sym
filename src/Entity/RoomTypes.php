<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoomTypesRepository")
 */
class RoomTypes
{
    /**
     * Many hostels have one user. This is the owning side.
     * @ORM\ManyToOne(targetEntity=Hostel::class, inversedBy="room_types")
     * @ORM\JoinColumn(name="hostel_id", referencedColumnName="id")
     */
    private $hostel;

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
     * @ORM\Column(type="decimal", precision=13, scale=4, nullable=true)
     */
    private $booking_fee;

    /**
     * @ORM\Column(type="boolean")
     */
    private $breakfast_included;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $currency;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $discounts = [];

    /**
     * @ORM\Column(type="decimal", precision=13, scale=4, nullable=true)
     */
    private $final_rate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $free_cancellation;

    /**
     * @ORM\Column(type="decimal", precision=13, scale=4, nullable=true)
     */
    private $hotel_fee;

    /**
     * @ORM\Column(type="string", length=7, nullable=true)
     */
    private $rate_type;

    /**
     * @ORM\Column(type="decimal", precision=13, scale=4, nullable=true)
     */
    private $local_tax;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $meal_code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $landing_page_url;

    /**
     * @ORM\Column(type="decimal", precision=13, scale=4, nullable=true)
     */
    private $net_rate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $payment_type;

    /**
     * @ORM\Column(type="decimal", precision=13, scale=4, nullable=true)
     */
    private $resort_fee;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $room_amenities = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $room_code;

    /**
     * @ORM\Column(type="decimal", precision=13, scale=4, nullable=true)
     */
    private $service_charge;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(type="decimal", precision=13, scale=4, nullable=true)
     */
    private $vat;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isUserMadeChanges;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isHandicappedAccessible;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $accommodation_type;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $number_of_units;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $unit_size;

    /**
     * @ORM\Column(type="string", length=4, nullable=true)
     */
    private $unit_type;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $unit_occupancy;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $number_of_bedrooms;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $number_of_bathrooms;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $floor_number;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $unit_number;

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

    public function getBookingFee(): ?string
    {
        return $this->booking_fee;
    }

    public function setBookingFee(string $booking_fee): self
    {
        $this->booking_fee = $booking_fee;

        return $this;
    }

    public function getBreakfastIncluded(): ?bool
    {
        return $this->breakfast_included;
    }

    public function setBreakfastIncluded(bool $breakfast_included): self
    {
        $this->breakfast_included = $breakfast_included;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getDiscounts(): ?array
    {
        return $this->discounts;
    }

    public function setDiscounts(?array $discounts): self
    {
        $this->discounts = $discounts;

        return $this;
    }

    public function getFinalRate(): ?string
    {
        return $this->final_rate;
    }

    public function setFinalRate(string $final_rate): self
    {
        $this->final_rate = $final_rate;

        return $this;
    }

    public function getFreeCancellation(): ?bool
    {
        return $this->free_cancellation;
    }

    public function setFreeCancellation(bool $free_cancellation): self
    {
        $this->free_cancellation = $free_cancellation;

        return $this;
    }

    public function getHotelFee(): ?string
    {
        return $this->hotel_fee;
    }

    public function setHotelFee(string $hotel_fee): self
    {
        $this->hotel_fee = $hotel_fee;

        return $this;
    }

    public function getRateType(): ?string
    {
        return $this->rate_type;
    }

    public function setRateType(?string $rate_type): self
    {
        $this->rate_type = $rate_type;

        return $this;
    }

    public function getLocalTax(): ?string
    {
        return $this->local_tax;
    }

    public function setLocalTax(string $local_tax): self
    {
        $this->local_tax = $local_tax;

        return $this;
    }

    public function getMealCode(): ?string
    {
        return $this->meal_code;
    }

    public function setMealCode(string $meal_code): self
    {
        $this->meal_code = $meal_code;

        return $this;
    }

    public function getLandingPageUrl(): ?string
    {
        return $this->landing_page_url;
    }

    public function setLandingPageUrl(?string $landing_page_url): self
    {
        $this->landing_page_url = $landing_page_url;

        return $this;
    }

    public function getNetRate(): ?string
    {
        return $this->net_rate;
    }

    public function setNetRate(string $net_rate): self
    {
        $this->net_rate = $net_rate;

        return $this;
    }

    public function getPaymentType(): ?string
    {
        return $this->payment_type;
    }

    public function setPaymentType(string $payment_type): self
    {
        $this->payment_type = $payment_type;

        return $this;
    }

    public function getResortFee(): ?string
    {
        return $this->resort_fee;
    }

    public function setResortFee(string $resort_fee): self
    {
        $this->resort_fee = $resort_fee;

        return $this;
    }

    public function getRoomAmenities(): ?array
    {
        return $this->room_amenities;
    }

    public function setRoomAmenities(?array $room_amenities): self
    {
        $this->room_amenities = $room_amenities;

        return $this;
    }

    public function getRoomCode(): ?string
    {
        return $this->room_code;
    }

    public function setRoomCode(string $room_code): self
    {
        $this->room_code = $room_code;

        return $this;
    }

    public function getServiceCharge(): ?string
    {
        return $this->service_charge;
    }

    public function setServiceCharge(string $service_charge): self
    {
        $this->service_charge = $service_charge;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getVat(): ?string
    {
        return $this->vat;
    }

    public function setVat(string $vat): self
    {
        $this->vat = $vat;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIsHandicappedAccessible(): ?bool
    {
        return $this->isHandicappedAccessible;
    }

    public function setIsHandicappedAccessible(?bool $isHandicappedAccessible): self
    {
        $this->isHandicappedAccessible = $isHandicappedAccessible;

        return $this;
    }

    public function getAccommodationType(): ?string
    {
        return $this->accommodation_type;
    }

    public function setAccommodationType(string $accommodation_type): self
    {
        $this->accommodation_type = $accommodation_type;

        return $this;
    }

    public function getNumberOfUnits(): ?int
    {
        return $this->number_of_units;
    }

    public function setNumberOfUnits(?int $number_of_units): self
    {
        $this->number_of_units = $number_of_units;

        return $this;
    }

    public function getUnitSize(): ?float
    {
        return $this->unit_size;
    }

    public function setUnitSize(?float $unit_size): self
    {
        $this->unit_size = $unit_size;

        return $this;
    }

    public function getUnitType(): ?string
    {
        return $this->unit_type;
    }

    public function setUnitType(?string $unit_type): self
    {
        $this->unit_type = $unit_type;

        return $this;
    }

    public function getUnitOccupancy(): ?int
    {
        return $this->unit_occupancy;
    }

    public function setUnitOccupancy(?int $unit_occupancy): self
    {
        $this->unit_occupancy = $unit_occupancy;

        return $this;
    }

    public function getNumberOfBedrooms(): ?float
    {
        return $this->number_of_bedrooms;
    }

    public function setNumberOfBedrooms(?float $number_of_bedrooms): self
    {
        $this->number_of_bedrooms = $number_of_bedrooms;

        return $this;
    }

    public function getNumberOfBathrooms(): ?float
    {
        return $this->number_of_bathrooms;
    }

    public function setNumberOfBathrooms(?float $number_of_bathrooms): self
    {
        $this->number_of_bathrooms = $number_of_bathrooms;

        return $this;
    }

    public function getFloorNumber(): ?int
    {
        return $this->floor_number;
    }

    public function setFloorNumber(?int $floor_number): self
    {
        $this->floor_number = $floor_number;

        return $this;
    }

    public function getUnitNumber(): ?string
    {
        return $this->unit_number;
    }

    public function setUnitNumber(?string $unit_number): self
    {
        $this->unit_number = $unit_number;

        return $this;
    }

}
