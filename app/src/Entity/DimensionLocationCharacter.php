<?php

namespace App\Entity;

use App\Repository\DimensionLocationCharacterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DimensionLocationCharacterRepository::class)]
#[ORM\Table(name: '`dimensions_locations_characters`')]
class DimensionLocationCharacter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'dimensionLocationCharacters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Dimension $dimension = null;

    #[ORM\ManyToOne(inversedBy: 'dimensionLocationCharacters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Location $location = null;

    #[ORM\ManyToOne(inversedBy: 'dimensionLocationCharacters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Character $character = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDimension(): ?Dimension
    {
        return $this->dimension;
    }

    public function setDimension(?Dimension $dimension): static
    {
        $this->dimension = $dimension;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getCharacter(): ?Character
    {
        return $this->character;
    }

    public function setCharacter(?Character $character): static
    {
        $this->character = $character;

        return $this;
    }
}
