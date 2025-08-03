<?php

namespace App\Entity;

use App\Enum\Gender;
use App\Enum\Status;
use App\Repository\CharacterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CharacterRepository::class)]
#[ORM\Table(name: '`characters`')]
class Character
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(enumType: Status::class)]
    private ?Status $status = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $species = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type = null;

    #[ORM\Column(enumType: Gender::class)]
    private ?Gender $gender = null;

    #[ORM\ManyToOne(inversedBy: 'image')]
    private ?Location $origin = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $image = null;

    /**
     * @var Collection<int, DimensionLocationCharacter>
     */
    #[ORM\OneToMany(targetEntity: DimensionLocationCharacter::class, mappedBy: 'character', orphanRemoval: true)]
    private Collection $dimensionLocationCharacters;

    /**
     * @var Collection<int, EpisodeCharacter>
     */
    #[ORM\OneToMany(targetEntity: EpisodeCharacter::class, mappedBy: 'character', orphanRemoval: true)]
    private Collection $episodeCharacters;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    public function __construct()
    {
        $this->dimensionLocationCharacters = new ArrayCollection();
        $this->episodeCharacters = new ArrayCollection();
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

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getSpecies(): ?string
    {
        return $this->species;
    }

    public function setSpecies(?string $species): static
    {
        $this->species = $species;

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

    public function getGender(): ?Gender
    {
        return $this->gender;
    }

    public function setGender(Gender $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getOrigin(): ?Location
    {
        return $this->origin;
    }

    public function setOrigin(?Location $origin): static
    {
        $this->origin = $origin;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

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
            $dimensionLocationCharacter->setCharacter($this);
        }

        return $this;
    }

    public function removeDimensionLocationCharacter(DimensionLocationCharacter $dimensionLocationCharacter): static
    {
        if ($this->dimensionLocationCharacters->removeElement($dimensionLocationCharacter)) {
            // set the owning side to null (unless already changed)
            if ($dimensionLocationCharacter->getCharacter() === $this) {
                $dimensionLocationCharacter->setCharacter(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EpisodeCharacter>
     */
    public function getEpisodeCharacters(): Collection
    {
        return $this->episodeCharacters;
    }

    public function addEpisodeCharacter(EpisodeCharacter $episodeCharacter): static
    {
        if (!$this->episodeCharacters->contains($episodeCharacter)) {
            $this->episodeCharacters->add($episodeCharacter);
            $episodeCharacter->setCharacter($this);
        }

        return $this;
    }

    public function removeEpisodeCharacter(EpisodeCharacter $episodeCharacter): static
    {
        if ($this->episodeCharacters->removeElement($episodeCharacter)) {
            // set the owning side to null (unless already changed)
            if ($episodeCharacter->getCharacter() === $this) {
                $episodeCharacter->setCharacter(null);
            }
        }

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }
}
