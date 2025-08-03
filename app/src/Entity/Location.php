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
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type = null;

    #[ORM\ManyToOne(inversedBy: 'locations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Dimension $dimension = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $url = null;

    /**
     * @var Collection<int, Character>
     */
    #[ORM\OneToMany(targetEntity: Character::class, mappedBy: 'origin')]
    private Collection $character;

    /**
     * @var Collection<int, DimensionLocationCharacter>
     */
    #[ORM\OneToMany(targetEntity: DimensionLocationCharacter::class, mappedBy: 'character', orphanRemoval: true)]
    private Collection $dimensionLocationCharacters;

    public function __construct()
    {
        $this->character = new ArrayCollection();
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return Collection<int, Character>
     */
    public function getCharacter(): Collection
    {
        return $this->character;
    }

    public function addCharacter(Character $character): static
    {
        if (!$this->character->contains($character)) {
            $this->character->add($character);
            $character->setOrigin($this);
        }

        return $this;
    }

    public function removeCharacter(Character $character): static
    {
        if ($this->character->removeElement($character)) {
            // set the owning side to null (unless already changed)
            if ($character->getOrigin() === $this) {
                $character->setOrigin(null);
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
            $dimensionLocationCharacter->setLocation($this);
        }

        return $this;
    }

    public function removeDimensionLocationCharacter(DimensionLocationCharacter $dimensionLocationCharacter): static
    {
        if ($this->dimensionLocationCharacters->removeElement($dimensionLocationCharacter)) {
            // set the owning side to null (unless already changed)
            if ($dimensionLocationCharacter->getLocation() === $this) {
                $dimensionLocationCharacter->setLocation(null);
            }
        }

        return $this;
    }
}
