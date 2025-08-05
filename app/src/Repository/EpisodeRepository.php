<?php

namespace App\Repository;

use App\Entity\Episode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Episode>
 */
class EpisodeRepository extends ServiceEntityRepository
{
    /**
     * Initializes the repository with the Episode entity class.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Episode::class);
    }
}
