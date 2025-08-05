<?php

namespace App\Entity;

use App\Repository\EpisodeCharacterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EpisodeCharacterRepository::class)]
#[ORM\Table(name: '`episodes_charactes`')]
class EpisodeCharacter
{
    /**
     * The unique identifier for the episode-character association.
     *
     * @var integer|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * The episode associated with the character.
     *
     * @var Episode|null
     */
    #[ORM\ManyToOne(inversedBy: 'episodeCharacters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Episode $episode = null;

    /**
     * The character appearing in the episode.
     *
     * @var Character|null
     */
    #[ORM\ManyToOne(inversedBy: 'episodeCharacters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Character $character = null;

    /**
     * Gets the ID of this episode-character link.
     *
     * @return integer|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the associated episode.
     *
     * @return Episode|null
     */
    public function getEpisode(): ?Episode
    {
        return $this->episode;
    }

    /**
     * Sets the associated episode.
     *
     * @param Episode|null $episode
     * @return static
     */
    public function setEpisode(?Episode $episode): static
    {
        $this->episode = $episode;
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
