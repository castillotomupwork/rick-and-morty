<?php

namespace App\Entity;

use App\Repository\EpisodeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EpisodeRepository::class)]
#[ORM\Table(name: '`episodes`')]
class Episode
{
    /**
     * The unique identifier for the episode.
     *
     * @var integer|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * The title or name of the episode.
     *
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * The air date of the episode (in string format).
     *
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $air_date = null;

    /**
     * The episode code (e.g., "S01E01").
     *
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $code = null;

    /**
     * The URL reference of the episode (usually from an API).
     *
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $url = null;

    /**
     * Characters that appear in this episode.
     *
     * @var Collection<int, EpisodeCharacter>
     */
    #[ORM\OneToMany(targetEntity: EpisodeCharacter::class, mappedBy: 'episode', orphanRemoval: true)]
    private Collection $episodeCharacters;

    /**
     * Initializes the collection of related characters.
     */
    public function __construct()
    {
        $this->episodeCharacters = new ArrayCollection();
    }

    /**
     * Gets the episode ID.
     *
     * @return integer|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the name of the episode.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Sets the name of the episode.
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
     * Gets the air date of the episode.
     *
     * @return string|null
     */
    public function getAirDate(): ?string
    {
        return $this->air_date;
    }

    /**
     * Sets the air date of the episode.
     *
     * @param string $air_date
     * @return static
     */
    public function setAirDate(string $air_date): static
    {
        $this->air_date = $air_date;
        return $this;
    }

    /**
     * Gets the episode code (e.g., "S02E05").
     *
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * Sets the episode code.
     *
     * @param string $code
     * @return static
     */
    public function setCode(string $code): static
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Gets the URL of the episode.
     *
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * Sets the URL of the episode.
     *
     * @param string $url
     * @return static
     */
    public function setUrl(string $url): static
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Gets the characters that appear in this episode.
     *
     * @return Collection<int, EpisodeCharacter>
     */
    public function getEpisodeCharacters(): Collection
    {
        return $this->episodeCharacters;
    }

    /**
     * Adds a character to the episode.
     *
     * @param EpisodeCharacter $episodeCharacter
     * @return static
     */
    public function addEpisodeCharacter(EpisodeCharacter $episodeCharacter): static
    {
        if (!$this->episodeCharacters->contains($episodeCharacter)) {
            $this->episodeCharacters->add($episodeCharacter);
            $episodeCharacter->setEpisode($this);
        }

        return $this;
    }

    /**
     * Removes a character from the episode.
     *
     * @param EpisodeCharacter $episodeCharacter
     * @return static
     */
    public function removeEpisodeCharacter(EpisodeCharacter $episodeCharacter): static
    {
        if ($this->episodeCharacters->removeElement($episodeCharacter)) {
            if ($episodeCharacter->getEpisode() === $this) {
                $episodeCharacter->setEpisode(null);
            }
        }

        return $this;
    }
}
