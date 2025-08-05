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
    /**
     * The unique identifier for the dimension.
     *
     * @var integer|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * The name of the dimension.
     *
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * Locations that belong to this dimension.
     *
     * @var Collection<int, Location>
     */
    #[ORM\OneToMany(targetEntity: Location::class, mappedBy: 'dimension', orphanRemoval: true)]
    private Collection $locations;

    /**
     * Character-location relationships associated with this dimension.
     *
     * @var Collection<int, DimensionLocationCharacter>
     */
    #[ORM\OneToMany(targetEntity: DimensionLocationCharacter::class, mappedBy: 'dimension', orphanRemoval: true)]
    private Collection $dimensionLocationCharacters;

    /**
     * Initializes collections for related entities.
     */
    public function __construct()
    {
        $this->locations = new ArrayCollection();
        $this->dimensionLocationCharacters = new ArrayCollection();
    }

    /**
     * Gets the dimension ID.
     *
     * @return integer|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the name of the dimension.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Sets the name of the dimension.
     *
     * @param string $name
     * @return static
     */
    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets all locations associated with this dimension.
     *
     * @return Collection<int, Location>
     */
    public function getLocations(): Collection
    {
        return $this->locations;
    }

    /**
     * Adds a location to this dimension.
     *
     * @param Location $location
     * @return static
     */
    public function addLocation(Location $location): static
    {
        if (!$this->locations->contains($location)) {
            $this->locations->add($location);
            $location->setDimension($this);
        }

        return $this;
    }

    /**
     * Removes a location from this dimension.
     *
     * @param Location $location
     * @return static
     */
    public function removeLocation(Location $location): static
    {
        if ($this->locations->removeElement($location)) {
            if ($location->getDimension() === $this) {
                $location->setDimension(null);
            }
        }

        return $this;
    }

    /**
     * Gets all character-dimension-location relationships.
     *
     * @return Collection<int, DimensionLocationCharacter>
     */
    public function getDimensionLocationCharacters(): Collection
    {
        return $this->dimensionLocationCharacters;
    }

    /**
     * Adds a character-dimension-location relationship.
     *
     * @param DimensionLocationCharacter $dimensionLocationCharacter
     * @return static
     */
    public function addDimensionLocationCharacter(DimensionLocationCharacter $dimensionLocationCharacter): static
    {
        if (!$this->dimensionLocationCharacters->contains($dimensionLocationCharacter)) {
            $this->dimensionLocationCharacters->add($dimensionLocationCharacter);
            $dimensionLocationCharacter->setDimension($this);
        }

        return $this;
    }

    /**
     * Removes a character-dimension-location relationship.
     *
     * @param DimensionLocationCharacter $dimensionLocationCharacter
     * @return static
     */
    public function removeDimensionLocationCharacter(DimensionLocationCharacter $dimensionLocationCharacter): static
    {
        if ($this->dimensionLocationCharacters->removeElement($dimensionLocationCharacter)) {
            if ($dimensionLocationCharacter->getDimension() === $this) {
                $dimensionLocationCharacter->setDimension(null);
            }
        }

        return $this;
    }
}
