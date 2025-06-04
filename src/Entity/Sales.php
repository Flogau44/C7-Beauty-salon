<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\SalesRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;

#[ORM\Entity(repositoryClass: SalesRepository::class)]
#[ApiResource]
#[GetCollection]
class Sales
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $amount = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\ManyToOne(inversedBy: 'sales')]
    private ?Salon $salon_id = null;

    #[ORM\ManyToOne(inversedBy: 'sales_id')]
    private ?SalesAverage $salesAverage = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getSalonId(): ?Salon
    {
        return $this->salon_id;
    }

    public function setSalonId(?Salon $salon_id): static
    {
        $this->salon_id = $salon_id;

        return $this;
    }

    public function getSalesAverage(): ?SalesAverage
    {
        return $this->salesAverage;
    }

    public function setSalesAverage(?SalesAverage $salesAverage): static
    {
        $this->salesAverage = $salesAverage;

        return $this;
    }
}
