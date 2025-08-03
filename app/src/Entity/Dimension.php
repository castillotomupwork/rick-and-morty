<?php

namespace App\Entity;

use App\Repository\DimensionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DimensionRepository::class)]
#[ORM\Table(name: '`dimensions`')]
class Dimension
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Location>
     */
    #[ORM\OneToMany(targetEntity: Location::class, mappedBy: 'dimension', orphanRemoval: true)]
    private Collection $locations;

    /**
     * @var Collection<int, DimensionLocationCharacter>
     */
    #[ORM\OneToMany(targetEntity: DimensionLocationCharacter::class, mappedBy: 'dimension', orphanRemoval: true)]
    private Collection $dimensionLocationCharacters;

    public function __construct()
    {
        $this->locations = new ArrayCollection();
        $this->dimensionLocationCharacters = new ArrayCollection();
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
     * @return Collection<int, Location>
     */
    public function getLocations(): Collection
    {
        return $this->locations;
    }

    public function addLocation(Location $location): static
    {
        if (!$this->locations->contains($location)) {
            $this->locations->add($location);
            $location->setDimension($this);
        }

        return $this;
    }

    public function removeLocation(Location $location): static
    {
        if ($this->locations->removeElement($location)) {
            // set the owning side to null (unless already changed)
            if ($location->getDimension() === $this) {
                $location->setDimension(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DimensionLocationCharacter>
     */
    public function getDimensionLocationCharacters(): Collection
    {
        return $this->dimensionLocationCharacters;
    }

    public function addDimensionLocationCharacter(DimensionLocationCharacter $dimensionLocationCharacter): static
    {
        if (!$this->dimensionLocationCharacters->contains($dimensionLocationCharacter)) {
            $this->dimensionLocationCharacters->add($dimensionLocationCharacter);
            $dimensionLocationCharacter->setDimension($this);
        }

        return $this;
    }

    public function removeDimensionLocationCharacter(DimensionLocationCharacter $dimensionLocationCharacter): static
    {
        if ($this->dimensionLocationCharacters->removeElement($dimensionLocationCharacter)) {
            // set the owning side to null (unless already changed)
            if ($dimensionLocationCharacter->getDimension() === $this) {
                $dimensionLocationCharacter->setDimension(null);
            }
        }

        return $this;
    }
}
