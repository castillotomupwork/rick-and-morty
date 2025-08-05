<?php

namespace App\Service;

use App\Entity\Character;
use App\Entity\Dimension;
use App\Entity\Episode;
use App\Entity\Location;
use App\Repository\CharacterRepository;
use App\Repository\DimensionRepository;
use App\Repository\EpisodeRepository;
use App\Repository\LocationRepository;
use Psr\Log\LoggerInterface;

class DatabaseDataProvider implements DataProviderInterface
{
    /**
     * Initializes the data provider with repositories and logger.
     *
     * @param CharacterRepository $characterRepository
     * @param DimensionRepository $dimensionRepository
     * @param LocationRepository $locationRepository
     * @param EpisodeRepository $episodeRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        private CharacterRepository $characterRepository,
        private DimensionRepository $dimensionRepository,
        private LocationRepository $locationRepository,
        private EpisodeRepository $episodeRepository,
        private LoggerInterface $logger
    ) {
    }

    /**
     * Returns a paginated and filtered list of characters from the database.
     *
     * @param array $params
     * @return array
     */
    public function getCharacters(array $params = []): array
    {
        $page = $params['page'] ?? 1;
        $dimension = $params['dimension'] ?? null;
        $location = $params['location'] ?? null;
        $episode = $params['episode'] ?? null;
        $status = $params['status'] ?? null;
        $species = $params['species'] ?? null;
        $gender = $params['gender'] ?? null;

        $limit = 20;

        try {
            $data = $this->characterRepository->findByFilters(
                (int) $dimension,
                (int) $location,
                (int) $episode,
                $status,
                $species,
                $gender,
                $limit,
                ($page - 1) * $limit
            );

            $characters = $data['characters'];

            $total = $data['total'];

            $pageTotal = (int) ceil($total / $limit);

            $charactersArray = array_map(function (Character $character) {
                $origin = $character->getOrigin();
                $originName = '';
                if ($origin) {
                    $originName = $origin->getName();
                }

                $dimensionLocations = $character->getDimensionLocationCharacters();

                $dimensionName = '';
                $locationName = '';
                if (!$dimensionLocations->isEmpty()) {
                    $last = $dimensionLocations->last();

                    $dimension = $last->getDimension();
                    if ($dimension) {
                        $dimensionName = $dimension->getName();
                    }

                    $location = $last->getLocation();
                    if ($location) {
                        $locationName = $location->getName();
                    }
                }

                $episodeName = '';
                $episodes = $character->getEpisodeCharacters();
                if (!$episodes->isEmpty()) {
                    $last = $episodes->last();

                    $episode = $last->getEpisode();
                    if ($episode) {
                        $episodeName = $episode->getName();
                    }
                }

                return [
                    'image' => $character->getImage(),
                    'name' => $character->getName(),
                    'species' => $character->getSpecies(),
                    'gender' => $character->getGender()?->label(),
                    'status' => $character->getStatus()?->label(),
                    'type' => $character->getType(),
                    'origin' => $originName,
                    'dimension' => $dimensionName,
                    'location' => $locationName,
                    'episode' => $episodeName
                ];
            }, $characters);

            return [
                'characters' => $charactersArray,
                'page' => $page,
                'pageTotal' => $pageTotal,
                'error' => null
            ];

        } catch (\Exception $e) {

            $this->logger->error($e->getMessage());

            return [
                'characters' => [],
                'page' => $page,
                'pageTotal' => 0,
                'error' => 'No characters found.'
            ];
        }
    }

    /**
     * Returns a list of dimensions from the database.
     *
     * @return array
     */
    public function getDimensions(): array
    {
        try {
            $dimensions = $this->dimensionRepository->findBy([], ['name' => 'ASC']);

            return array_map(function (Dimension $dimension) {
                return [
                    'id' => $dimension->getId(),
                    'name' => $dimension->getName()
                ];
            }, $dimensions);

        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());

            return [];
        }
    }

    /**
     * Returns a list of locations from the database.
     *
     * @return array
     */
    public function getLocations(): array
    {
        try {
            $locations = $this->locationRepository->findBy([], ['name' => 'ASC']);

            return array_map(function (Location $location) {
                return [
                    'id' => $location->getId(),
                    'name' => $location->getName()
                ];
            }, $locations);

        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());

            return [];
        }
    }

    /**
     * Returns a list of episodes from the database.
     *
     * @return array
     */
    public function getEpisodes(): array
    {
        try {
            $episodes = $this->episodeRepository->findBy([], ['name' => 'ASC']);

            return array_map(function (Episode $episode) {
                return [
                    'id' => $episode->getId(),
                    'name' => $episode->getName()
                ];
            }, $episodes);

        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());

            return [];
        }
    }
}