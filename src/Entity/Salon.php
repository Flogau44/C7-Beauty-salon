<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\SalonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SalonRepository::class)]
#[ApiResource]
class Salon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(length: 255)]
    private ?string $staff_number = null;

    #[ORM\Column(length: 255)]
    private ?string $opening_date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getStaffNumber(): ?string
    {
        return $this->staff_number;
    }

    public function setStaffNumber(string $staff_number): static
    {
        $this->staff_number = $staff_number;

        return $this;
    }

    public function getOpeningDate(): ?string
    {
        return $this->opening_date;
    }

    public function setOpeningDate(string $opening_date): static
    {
        $this->opening_date = $opening_date;

        return $this;
    }
}
