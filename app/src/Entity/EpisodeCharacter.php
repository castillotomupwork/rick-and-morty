<?php

namespace App\Entity;

use App\Repository\EpisodeCharacterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EpisodeCharacterRepository::class)]
#[ORM\Table(name: '`episodes_charactes`')]
class EpisodeCharacter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'episodeCharacters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Episode $episode = null;

    #[ORM\ManyToOne(inversedBy: 'episodeCharacters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Character $character = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEpisode(): ?Episode
    {
        return $this->episode;
    }

    public function setEpisode(?Episode $episode): static
    {
        $this->episode = $episode;

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
