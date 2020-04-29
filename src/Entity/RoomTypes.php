<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoomTypesRepository")
 */
class RoomTypes
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
     * @ORM\Column(type="float")
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
     * @ORM\Column(type="float")
     */
    private $final_rate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $free_cancellation;

    /**
     * @ORM\Column(type="float")
     */
    private $hotel_fee;

    /**
     * @ORM\Column(type="string", length=7, nullable=true)
     */
    private $rate_type;

    /**
     * @ORM\Column(type="float")
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
     * @ORM\Column(type="float")
     */
    private $net_rate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $payment_type;

    /**
     * @ORM\Column(type="float")
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
     * @ORM\Column(type="float")
     */
    private $service_charge;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(type="float")
     */
    private $vat;

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

    public function getBookingFee(): ?float
    {
        return $this->booking_fee;
    }

    public function setBookingFee(float $booking_fee): self
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

    public function getFinalRate(): ?float
    {
        return $this->final_rate;
    }

    public function setFinalRate(float $final_rate): self
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

    public function getHotelFee(): ?float
    {
        return $this->hotel_fee;
    }

    public function setHotelFee(float $hotel_fee): self
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

    public function getLocalTax(): ?float
    {
        return $this->local_tax;
    }

    public function setLocalTax(float $local_tax): self
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

    public function getNetRate(): ?float
    {
        return $this->net_rate;
    }

    public function setNetRate(float $net_rate): self
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

    public function getResortFee(): ?float
    {
        return $this->resort_fee;
    }

    public function setResortFee(float $resort_fee): self
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

    public function getServiceCharge(): ?float
    {
        return $this->service_charge;
    }

    public function setServiceCharge(float $service_charge): self
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

    public function getVat(): ?float
    {
        return $this->vat;
    }

    public function setVat(float $vat): self
    {
        $this->vat = $vat;

        return $this;
    }
}
