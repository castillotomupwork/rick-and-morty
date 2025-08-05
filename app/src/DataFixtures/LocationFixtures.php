<?php

namespace App\DataFixtures;

use App\Entity\Dimension;
use App\Entity\Location;
use App\Repository\DimensionRepository;
use App\Repository\LocationRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class LocationFixtures extends Fixture implements FixtureGroupInterface
{
    /**
     * Constructor for location fixture loading.
     * Initializes required services or helpers for populating location data.
     *
     * @param HttpClientInterface $client
     * @param string $apiUrl
     * @param LoggerInterface $logger
     * @param DimensionRepository $dimensionRepository
     * @param LocationRepository $locationRepository
     */
    public function __construct(
        private HttpClientInterface $client,
        private string $apiUrl,
        private LoggerInterface $logger,
        private DimensionRepository $dimensionRepository,
        private LocationRepository $locationRepository
    ) {
        $this->apiUrl = rtrim($apiUrl, '/');
    }

    /**
     * Returns the groups this fixture belongs to.
     *
     * @return array
     */
    public static function getGroups(): array
    {
        return ['group1'];
    }

    /**
     * Loads location fixture data into the database.
     *
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void 
    {
        $page = 1;
        $totalPage = 0;

        do {
            try {
                $response = $this->client->request(
                    'GET', 
                    $this->apiUrl . '/location?page=' . $page
                );

                $data = $response->toArray();

                $info = $data['info'] ?? null;

                if (!is_null($info)) {
                    $totalPage = $info['pages'] ?? 0;
                }

                $results = $data['results'] ?? [];

                foreach ($results as $row) {
                    $dimension = $this->dimensionRepository->findOneBy([
                        'name' => $row['dimension']
                    ]);
                    
                    if (!$dimension) {
                        $dimension = new Dimension();
                        $dimension->setName($row['dimension']);
                        $manager->persist($dimension);
                        $manager->flush();
                    }
                    
                    $location = new Location();
                    $location->setDimension($dimension);
                    $location->setName($row['name']);
                    $location->setType($row['type']);
                    $location->setUrl($row['url']);
                    $manager->persist($location);
                    $manager->flush();
                }

                $page++;
                
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
                break;
            }

        } while ($page <= $totalPage);
    }
}
