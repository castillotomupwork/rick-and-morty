<?php

namespace App\Repository;

use App\Entity\Dimension;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Dimension>
 */
class DimensionRepository extends ServiceEntityRepository
{
    /**
     * Initializes the repository with the Dimension entity class.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dimension::class);
    }
}
