<?php

namespace App\Repository;

use App\Entity\EpisodeCharacter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EpisodeCharacter>
 */
class EpisodeCharacterRepository extends ServiceEntityRepository
{
    /**
     * Initializes the repository with the EpisodeCharacter entity class.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EpisodeCharacter::class);
    }
}
