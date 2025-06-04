<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\CountryRepository;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: CountryRepository::class)]
#[ApiResource]
#[GetCollection]
class Country
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Region>
     */
    #[ORM\OneToMany(targetEntity: Region::class, mappedBy: 'country_id')]
    private Collection $regions;

    public function __construct()
    {
        $this->regions = new ArrayCollection();
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

    /**
     * @return Collection<int, Region>
     */
    public function getRegions(): Collection
    {
        return $this->regions;
    }

    public function addRegion(Region $region): static
    {
        if (!$this->regions->contains($region)) {
            $this->regions->add($region);
            $region->setCountryId($this);
        }

        return $this;
    }

    public function removeRegion(Region $region): static
    {
        if ($this->regions->removeElement($region)) {
            // set the owning side to null (unless already changed)
            if ($region->getCountryId() === $this) {
                $region->setCountryId(null);
            }
        }

        return $this;
    }
}
