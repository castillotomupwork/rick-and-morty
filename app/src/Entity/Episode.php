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
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $air_date = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    /**
     * @var Collection<int, EpisodeCharacter>
     */
    #[ORM\OneToMany(targetEntity: EpisodeCharacter::class, mappedBy: 'episode', orphanRemoval: true)]
    private Collection $episodeCharacters;

    public function __construct()
    {
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

    public function getAirDate(): ?string
    {
        return $this->air_date;
    }

    public function setAirDate(string $air_date): static
    {
        $this->air_date = $air_date;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

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
            $episodeCharacter->setEpisode($this);
        }

        return $this;
    }

    public function removeEpisodeCharacter(EpisodeCharacter $episodeCharacter): static
    {
        if ($this->episodeCharacters->removeElement($episodeCharacter)) {
            // set the owning side to null (unless already changed)
            if ($episodeCharacter->getEpisode() === $this) {
                $episodeCharacter->setEpisode(null);
            }
        }

        return $this;
    }
}
