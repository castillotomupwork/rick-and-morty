<?php

namespace App\Repository;

use App\Entity\DimensionLocationCharacter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DimensionLocationCharacter>
 */
class DimensionLocationCharacterRepository extends ServiceEntityRepository
{
    /**
     * Initializes the repository with the DimensionLocationCharacter entity class.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DimensionLocationCharacter::class);
    }
}
