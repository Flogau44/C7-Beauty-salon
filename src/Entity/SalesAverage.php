<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\SalesAverageRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: SalesAverageRepository::class)]
#[ApiResource]
#[GetCollection]
class SalesAverage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $amount_average = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    /**
     * @var Collection<int, sales>
     */
    #[ORM\OneToMany(targetEntity: Sales::class, mappedBy: 'salesAverage')]
    private Collection $sales_id;

    public function __construct()
    {
        $this->sales_id = new ArrayCollection();
    }

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

    public function getAmountAverage(): ?string
    {
        return $this->amount_average;
    }

    public function setAmountAverage(string $amount_average): static
    {
        $this->amount_average = $amount_average;

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

    /**
     * @return Collection<int, sales>
     */
    public function getSalesId(): Collection
    {
        return $this->sales_id;
    }

    public function addSalesId(Sales $salesId): static
    {
        if (!$this->sales_id->contains($salesId)) {
            $this->sales_id->add($salesId);
            $salesId->setSalesAverage($this);
        }

        return $this;
    }

    public function removeSalesId(Sales $salesId): static
    {
        if ($this->sales_id->removeElement($salesId)) {
            // set the owning side to null (unless already changed)
            if ($salesId->getSalesAverage() === $this) {
                $salesId->setSalesAverage(null);
            }
        }

        return $this;
    }
}
