<?php

namespace App\DataFixtures;

use App\Entity\Character;
use App\Entity\DimensionLocationCharacter;
use App\Enum\Gender;
use App\Enum\Status;
use App\Repository\LocationRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CharacterFixtures extends Fixture implements FixtureGroupInterface
{

    /**
     * Constructor for character fixture loading.
     * Initializes required services or helpers for populating character data.
     *
     * @param HttpClientInterface $client
     * @param string $apiUrl
     * @param LoggerInterface $logger
     * @param LocationRepository $locationRepository
     */
    public function __construct(
        private HttpClientInterface $client,
        private string $apiUrl,
        private LoggerInterface $logger,
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
        return ['group2'];
    }

    /**
     * Loads character fixture data into the database.
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
                    $this->apiUrl . '/character?page=' . $page
                );

                $data = $response->toArray();

                $info = $data['info'] ?? null;

                if (!is_null($info)) {
                    $totalPage = $info['pages'] ?? 0;
                }

                $results = $data['results'] ?? [];

                foreach ($results as $row) {
                    $status = Status::safeFrom($row['status']) ?? Status::UNKNOWN;

                    $gender = Gender::safeFrom($row['gender']) ?? Gender::UNKNOWN;

                    $origin = null;
                    $originArr = $row['origin'] ?? null;
                    if ($originArr) {
                        $origin = $this->locationRepository->findOneBy([
                            'name' => $originArr['name']
                        ]);
                    }

                    $character = new Character();
                    $character->setName($row['name']);
                    $character->setStatus($status);
                    $character->setSpecies($row['species']);
                    $character->setType($row['type']);
                    $character->setGender($gender);
                    $character->setImage($row['image']);
                    $character->setUrl($row['url']);

                    if ($origin) {
                        $character->setOrigin($origin);
                    }

                    $manager->persist($character);
                    $manager->flush();

                    $location = null;
                    $locationArr = $row['location'] ?? null;
                    if ($locationArr) {
                        $location = $this->locationRepository->findOneBy([
                            'name' => $locationArr['name']
                        ]);
                    }

                    if ($location) {
                        $dimension = $location->getDimension();

                        $dimensionLocationCharacter = new DimensionLocationCharacter();
                        $dimensionLocationCharacter->setDimension($dimension);
                        $dimensionLocationCharacter->setLocation($location);
                        $dimensionLocationCharacter->setCharacter($character);
                        $manager->persist($dimensionLocationCharacter);
                        $manager->flush();
                    }
                }

                $page++;
                
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
                break;
            }

        } while ($page <= $totalPage);
    }
}
