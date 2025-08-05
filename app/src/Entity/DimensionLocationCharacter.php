<?php

namespace App\Entity;

use App\Repository\DimensionLocationCharacterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DimensionLocationCharacterRepository::class)]
#[ORM\Table(name: '`dimensions_locations_characters`')]
class DimensionLocationCharacter
{
    /**
     * The unique identifier for the dimension-location-character association.
     *
     * @var integer|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * The associated dimension.
     *
     * @var Dimension|null
     */
    #[ORM\ManyToOne(inversedBy: 'dimensionLocationCharacters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Dimension $dimension = null;

    /**
     * The associated location within the dimension.
     *
     * @var Location|null
     */
    #[ORM\ManyToOne(inversedBy: 'dimensionLocationCharacters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Location $location = null;

    /**
     * The character related to this dimension-location pair.
     *
     * @var Character|null
     */
    #[ORM\ManyToOne(inversedBy: 'dimensionLocationCharacters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Character $character = null;

    /**
     * Gets the ID of this association.
     *
     * @return integer|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the associated dimension.
     *
     * @return Dimension|null
     */
    public function getDimension(): ?Dimension
    {
        return $this->dimension;
    }

    /**
     * Sets the associated dimension.
     *
     * @param Dimension|null $dimension
     * @return static
     */
    public function setDimension(?Dimension $dimension): static
    {
        $this->dimension = $dimension;
        return $this;
    }

    /**
     * Gets the associated location.
     *
     * @return Location|null
     */
    public function getLocation(): ?Location
    {
        return $this->location;
    }

    /**
     * Sets the associated location.
     *
     * @param Location|null $location
     * @return static
     */
    public function setLocation(?Location $location): static
    {
        $this->location = $location;
        return $this;
    }

    /**
     * Gets the associated character.
     *
     * @return Character|null
     */
    public function getCharacter(): ?Character
    {
        return $this->character;
    }

    /**
     * Sets the associated character.
     *
     * @param Character|null $character
     * @return static
     */
    public function setCharacter(?Character $character): static
    {
        $this->character = $character;
        return $this;
    }
}
