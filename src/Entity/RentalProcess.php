<?php

namespace App\Entity;

use App\Repository\RentalProcessRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RentalProcessRepository::class)]
class RentalProcess
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'rentalProcesses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Reservation $reservation = null;

    #[ORM\ManyToOne(inversedBy: 'rentalProcesses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Car $car = null;

    #[ORM\ManyToOne(inversedBy: 'rentalProcesses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $pickupType = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $deliveryAddress = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $pickupFuelLevel = null;

    #[ORM\Column(nullable: true)]
    private ?int $pickupKilometers = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $returnFuelLevel = null;

    #[ORM\Column(nullable: true)]
    private ?int $returnKilometers = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTime $pickupTime = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    public function setReservation(?Reservation $reservation): static
    {
        $this->reservation = $reservation;

        return $this;
    }

    public function getCar(): ?Car
    {
        return $this->car;
    }

    public function setCar(?Car $car): static
    {
        $this->car = $car;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getPickupType(): ?string
    {
        return $this->pickupType;
    }

    public function setPickupType(?string $pickupType): static
    {
        $this->pickupType = $pickupType;

        return $this;
    }

    public function getDeliveryAddress(): ?string
    {
        return $this->deliveryAddress;
    }

    public function setDeliveryAddress(?string $deliveryAddress): static
    {
        $this->deliveryAddress = $deliveryAddress;

        return $this;
    }

    public function getPickupFuelLevel(): ?string
    {
        return $this->pickupFuelLevel;
    }

    public function setPickupFuelLevel(?string $pickupFuelLevel): static
    {
        $this->pickupFuelLevel = $pickupFuelLevel;

        return $this;
    }

    public function getPickupKilometers(): ?int
    {
        return $this->pickupKilometers;
    }

    public function setPickupKilometers(?int $pickupKilometers): static
    {
        $this->pickupKilometers = $pickupKilometers;

        return $this;
    }

    public function getReturnFuelLevel(): ?string
    {
        return $this->returnFuelLevel;
    }

    public function setReturnFuelLevel(?string $returnFuelLevel): static
    {
        $this->returnFuelLevel = $returnFuelLevel;

        return $this;
    }

    public function getReturnKilometers(): ?int
    {
        return $this->returnKilometers;
    }

    public function setReturnKilometers(?int $returnKilometers): static
    {
        $this->returnKilometers = $returnKilometers;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPickupTime(): ?\DateTime
    {
        return $this->pickupTime;
    }

    public function setPickupTime(?\DateTime $pickupTime): static
    {
        $this->pickupTime = $pickupTime;

        return $this;
    }
}
