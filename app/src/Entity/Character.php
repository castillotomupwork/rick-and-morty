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
    /**
     * The unique identifier for the character.
     *
     * @var integer|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * The name of the character.
     *
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * The current status of the character (e.g., Alive, Dead, Unknown).
     *
     * @var Status|null
     */
    #[ORM\Column(enumType: Status::class)]
    private ?Status $status = null;

    /**
     * The species of the character (e.g., Human, Alien).
     *
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $species = null;

    /**
     * The character's subtype or variation (if any).
     *
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type = null;

    /**
     * The gender of the character.
     *
     * @var Gender|null
     */
    #[ORM\Column(enumType: Gender::class)]
    private ?Gender $gender = null;

    /**
     * The origin location of the character.
     *
     * @var Location|null
     */
    #[ORM\ManyToOne(inversedBy: 'image')]
    private ?Location $origin = null;

    /**
     * The URL or path to the character's image.
     *
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $image = null;

    /**
     * Collection of dimension-location mappings associated with the character.
     *
     * @var Collection<int, DimensionLocationCharacter>
     */
    #[ORM\OneToMany(targetEntity: DimensionLocationCharacter::class, mappedBy: 'character', orphanRemoval: true)]
    private Collection $dimensionLocationCharacters;

    /**
     * Collection of episodes the character appears in.
     *
     * @var Collection<int, EpisodeCharacter>
     */
    #[ORM\OneToMany(targetEntity: EpisodeCharacter::class, mappedBy: 'character', orphanRemoval: true)]
    private Collection $episodeCharacters;

    /**
     * The full URL of the character (usually from an API or canonical reference).
     *
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $url = null;

    /**
     * Initializes collections for related entities.
     */
    public function __construct()
    {
        $this->dimensionLocationCharacters = new ArrayCollection();
        $this->episodeCharacters = new ArrayCollection();
    }

    /**
     * Gets the character ID.
     *
     * @return integer|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the character's name.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Sets the character's name.
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
     * Gets the character's status.
     *
     * @return Status|null
     */
    public function getStatus(): ?Status
    {
        return $this->status;
    }

    /**
     * Sets the character's status.
     *
     * @param Status $status
     * @return static
     */
    public function setStatus(Status $status): static
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Gets the character's species.
     *
     * @return string|null
     */
    public function getSpecies(): ?string
    {
        return $this->species;
    }

    /**
     * Sets the character's species.
     *
     * @param string|null $species
     * @return static
     */
    public function setSpecies(?string $species): static
    {
        $this->species = $species;
        return $this;
    }

    /**
     * Gets the character's type.
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Sets the character's type.
     *
     * @param string|null $type
     * @return static
     */
    public function setType(?string $type): static
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Gets the character's gender.
     *
     * @return Gender|null
     */
    public function getGender(): ?Gender
    {
        return $this->gender;
    }

    /**
     * Sets the character's gender.
     *
     * @param Gender $gender
     * @return static
     */
    public function setGender(Gender $gender): static
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * Gets the origin location of the character.
     *
     * @return Location|null
     */
    public function getOrigin(): ?Location
    {
        return $this->origin;
    }

    /**
     * Sets the origin location of the character.
     *
     * @param Location|null $origin
     * @return static
     */
    public function setOrigin(?Location $origin): static
    {
        $this->origin = $origin;
        return $this;
    }

    /**
     * Gets the image URL of the character.
     *
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * Sets the image URL of the character.
     *
     * @param string|null $image
     * @return static
     */
    public function setImage(?string $image): static
    {
        $this->image = $image;
        return $this;
    }

    /**
     * Gets the character's dimension-location associations.
     *
     * @return Collection<int, DimensionLocationCharacter>
     */
    public function getDimensionLocationCharacters(): Collection
    {
        return $this->dimensionLocationCharacters;
    }

    /**
     * Adds a dimension-location association to the character.
     *
     * @param DimensionLocationCharacter $dimensionLocationCharacter
     * @return static
     */
    public function addDimensionLocationCharacter(DimensionLocationCharacter $dimensionLocationCharacter): static
    {
        if (!$this->dimensionLocationCharacters->contains($dimensionLocationCharacter)) {
            $this->dimensionLocationCharacters->add($dimensionLocationCharacter);
            $dimensionLocationCharacter->setCharacter($this);
        }

        return $this;
    }

    /**
     * Removes a dimension-location association from the character.
     *
     * @param DimensionLocationCharacter $dimensionLocationCharacter
     * @return static
     */
    public function removeDimensionLocationCharacter(DimensionLocationCharacter $dimensionLocationCharacter): static
    {
        if ($this->dimensionLocationCharacters->removeElement($dimensionLocationCharacter)) {
            if ($dimensionLocationCharacter->getCharacter() === $this) {
                $dimensionLocationCharacter->setCharacter(null);
            }
        }

        return $this;
    }

    /**
     * Gets the episodes the character appears in.
     *
     * @return Collection<int, EpisodeCharacter>
     */
    public function getEpisodeCharacters(): Collection
    {
        return $this->episodeCharacters;
    }

    /**
     * Adds an episode to the character's appearance list.
     *
     * @param EpisodeCharacter $episodeCharacter
     * @return static
     */
    public function addEpisodeCharacter(EpisodeCharacter $episodeCharacter): static
    {
        if (!$this->episodeCharacters->contains($episodeCharacter)) {
            $this->episodeCharacters->add($episodeCharacter);
            $episodeCharacter->setCharacter($this);
        }

        return $this;
    }

    /**
     * Removes an episode from the character's appearance list.
     *
     * @param EpisodeCharacter $episodeCharacter
     * @return static
     */
    public function removeEpisodeCharacter(EpisodeCharacter $episodeCharacter): static
    {
        if ($this->episodeCharacters->removeElement($episodeCharacter)) {
            if ($episodeCharacter->getCharacter() === $this) {
                $episodeCharacter->setCharacter(null);
            }
        }

        return $this;
    }

    /**
     * Gets the full URL reference of the character.
     *
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * Sets the full URL reference of the character.
     *
     * @param string $url
     * @return static
     */
    public function setUrl(string $url): static
    {
        $this->url = $url;
        return $this;
    }
}
