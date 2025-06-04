<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\SalonRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: SalonRepository::class)]
#[ApiResource]
#[GetCollection]
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

    #[ORM\Column]
    private ?\DateTimeImmutable $opening_date = null;

    #[ORM\ManyToOne(inversedBy: 'salons')]
    private ?Department $department_id = null;

    /**
     * @var Collection<int, user>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'salon')]
    private Collection $user_id;

    /**
     * @var Collection<int, Sales>
     */
    #[ORM\OneToMany(targetEntity: Sales::class, mappedBy: 'salon_id')]
    private Collection $sales;

    public function __construct()
    {
        $this->user_id = new ArrayCollection();
        $this->sales = new ArrayCollection();
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

    public function getOpeningDate(): ?\DateTimeImmutable
    {
        return $this->opening_date;
    }

    public function setOpeningDate(\DateTimeImmutable $opening_date): static
    {
        $this->opening_date = $opening_date;

        return $this;
    }

    public function getDepartmentId(): ?Department
    {
        return $this->department_id;
    }

    public function setDepartmentId(?Department $department_id): static
    {
        $this->department_id = $department_id;

        return $this;
    }

    /**
     * @return Collection<int, user>
     */
    public function getUserId(): Collection
    {
        return $this->user_id;
    }

    public function addUserId(User $userId): static
    {
        if (!$this->user_id->contains($userId)) {
            $this->user_id->add($userId);
            $userId->setSalon($this);
        }

        return $this;
    }

    public function removeUserId(User $userId): static
    {
        if ($this->user_id->removeElement($userId)) {
            // set the owning side to null (unless already changed)
            if ($userId->getSalon() === $this) {
                $userId->setSalon(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Sales>
     */
    public function getSales(): Collection
    {
        return $this->sales;
    }

    public function addSale(Sales $sale): static
    {
        if (!$this->sales->contains($sale)) {
            $this->sales->add($sale);
            $sale->setSalonId($this);
        }

        return $this;
    }

    public function removeSale(Sales $sale): static
    {
        if ($this->sales->removeElement($sale)) {
            // set the owning side to null (unless already changed)
            if ($sale->getSalonId() === $this) {
                $sale->setSalonId(null);
            }
        }

        return $this;
    }
}
