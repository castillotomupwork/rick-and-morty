<?php

namespace App\Repository;

use App\Entity\Character;
use App\Enum\Gender;
use App\Enum\Status;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Character>
 */
class CharacterRepository extends ServiceEntityRepository
{
    /**
     * Initializes the repository with the Character entity class.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Character::class);
    }

    /**
     * Finds characters filtered by dimension, location, episode, status, species, and gender.
     * Supports pagination via limit and offset.
     *
     * Returns both the list of characters and the total count of matching records.
     *
     * @param integer|null $dimensionId
     * @param integer|null $locationId
     * @param integer|null $episodeId
     * @param integer|null $status
     * @param integer|null $species
     * @param integer|null $gender
     * @param integer $limit
     * @param integer $offset
     * @return array
     */
    public function findByFilters(
        int|null $dimensionId,
        int|null $locationId,
        int|null $episodeId,
        string|null $status,
        string|null $species,
        string|null $gender,
        int $limit = 10,
        int $offset = 0
    ): array {
        $query = $this->createQueryBuilder('c')
            ->where('1 = 1');

        if (!empty($status) && !is_null(Status::safeFrom($status))) {
            $query->andWhere('c.status = :status')
                ->setParameter(':status', $status);
        }

        if (!empty($species)) {
            $query->andWhere('c.species = :species')
                ->setParameter(':species', $species);
        }

        if (!empty($gender) && !is_null(Gender::safeFrom($gender))) {
            $query->andWhere('c.gender = :gender')
                ->setParameter(':gender', $gender);
        }

        if (is_int($dimensionId) || is_int($locationId)) {
            $query->innerJoin('c.dimensionLocationCharacters', 'dlc');

            if (is_int($dimensionId) && $dimensionId > 0) {
                $query->andWhere('dlc.dimension = :dimensionId')
                ->setParameter('dimensionId', $dimensionId);
            }

            if (is_int($locationId) && $locationId > 0) {
                $query->andWhere('dlc.location = :locationId')
                ->setParameter('locationId', $locationId);
            }
        }

        if (is_int($episodeId) && $episodeId > 0) {
            $query->innerJoin('c.episodeCharacters', 'ec')
                ->andWhere('ec.episode = :episodeId')
                ->setParameter('episodeId', $episodeId);
        }

        $characters = $query
            ->orderBy('c.name', 'ASC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();

        $total = (clone $query)
            ->select('COUNT(DISTINCT c.id)')
            ->setFirstResult(null)
            ->setMaxResults(null)
            ->getQuery()
            ->getSingleScalarResult();

        return [
            'characters' => $characters,
            'total' => $total
        ];
    }

}
