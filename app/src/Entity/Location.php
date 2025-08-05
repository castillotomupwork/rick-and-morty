<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
#[ORM\Table(name: '`locations`')]
class Location
{
    /**
     * The unique identifier for the location.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * The name of the location.
     *
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * The type/category of the location (e.g., planet, space station).
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type = null;

    /**
     * The dimension this location belongs to.
     */
    #[ORM\ManyToOne(inversedBy: 'locations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Dimension $dimension = null;

    /**
     * URL reference of the location (usually from an external API).
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $url = null;

    /**
     * Characters whose origin is this location.
     *
     * @var Collection<int, Character>
     */
    #[ORM\OneToMany(targetEntity: Character::class, mappedBy: 'origin')]
    private Collection $character;

    /**
     * Relationships between this location, dimensions, and characters.
     *
     * @var Collection<int, DimensionLocationCharacter>
     */
    #[ORM\OneToMany(targetEntity: DimensionLocationCharacter::class, mappedBy: 'character', orphanRemoval: true)]
    private Collection $dimensionLocationCharacters;

    /**
     * Initializes related collections.
     */
    public function __construct()
    {
        $this->character = new ArrayCollection();
        $this->dimensionLocationCharacters = new ArrayCollection();
    }

    /**
     * Gets the ID of the location.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the name of the location.
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Sets the name of the location.
     */
    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets the type of the location.
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Sets the type of the location.
     */
    public function setType(?string $type): static
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Gets the dimension associated with the location.
     */
    public function getDimension(): ?Dimension
    {
        return $this->dimension;
    }

    /**
     * Sets the dimension associated with the location.
     */
    public function setDimension(?Dimension $dimension): static
    {
        $this->dimension = $dimension;
        return $this;
    }

    /**
     * Gets the URL reference of the location.
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * Sets the URL reference of the location.
     */
    public function setUrl(?string $url): static
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Gets characters whose origin is this location.
     *
     * @return Collection<int, Character>
     */
    public function getCharacter(): Collection
    {
        return $this->character;
    }

    /**
     * Adds a character to this location.
     */
    public function addCharacter(Character $character): static
    {
        if (!$this->character->contains($character)) {
            $this->character->add($character);
            $character->setOrigin($this);
        }

        return $this;
    }

    /**
     * Removes a character from this location.
     */
    public function removeCharacter(Character $character): static
    {
        if ($this->character->removeElement($character)) {
            if ($character->getOrigin() === $this) {
                $character->setOrigin(null);
            }
        }

        return $this;
    }

    /**
     * Gets dimension-location-character relationships tied to this location.
     *
     * @return Collection<int, DimensionLocationCharacter>
     */
    public function getDimensionLocationCharacters(): Collection
    {
        return $this->dimensionLocationCharacters;
    }

    /**
     * Adds a dimension-location-character relationship.
     */
    public function addDimensionLocationCharacter(DimensionLocationCharacter $dimensionLocationCharacter): static
    {
        if (!$this->dimensionLocationCharacters->contains($dimensionLocationCharacter)) {
            $this->dimensionLocationCharacters->add($dimensionLocationCharacter);
            $dimensionLocationCharacter->setLocation($this);
        }

        return $this;
    }

    /**
     * Removes a dimension-location-character relationship.
     */
    public function removeDimensionLocationCharacter(DimensionLocationCharacter $dimensionLocationCharacter): static
    {
        if ($this->dimensionLocationCharacters->removeElement($dimensionLocationCharacter)) {
            if ($dimensionLocationCharacter->getLocation() === $this) {
                $dimensionLocationCharacter->setLocation(null);
            }
        }

        return $this;
    }
}
