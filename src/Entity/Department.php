<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\DepartmentRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: DepartmentRepository::class)]
#[ApiResource]
#[GetCollection]
class Department
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $number = null;

    #[ORM\ManyToOne(inversedBy: 'departments')]
    private ?Region $region_id = null;

    /**
     * @var Collection<int, Salon>
     */
    #[ORM\OneToMany(targetEntity: Salon::class, mappedBy: 'department_id')]
    private Collection $salons;

    public function __construct()
    {
        $this->salons = new ArrayCollection();
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

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getRegionId(): ?Region
    {
        return $this->region_id;
    }

    public function setRegionId(?Region $region_id): static
    {
        $this->region_id = $region_id;

        return $this;
    }

    /**
     * @return Collection<int, Salon>
     */
    public function getSalons(): Collection
    {
        return $this->salons;
    }

    public function addSalon(Salon $salon): static
    {
        if (!$this->salons->contains($salon)) {
            $this->salons->add($salon);
            $salon->setDepartmentId($this);
        }

        return $this;
    }

    public function removeSalon(Salon $salon): static
    {
        if ($this->salons->removeElement($salon)) {
            // set the owning side to null (unless already changed)
            if ($salon->getDepartmentId() === $this) {
                $salon->setDepartmentId(null);
            }
        }

        return $this;
    }
}
