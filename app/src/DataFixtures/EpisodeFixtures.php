<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use App\Entity\EpisodeCharacter;
use App\Repository\CharacterRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class EpisodeFixtures extends Fixture implements FixtureGroupInterface
{
    public function __construct(
        private HttpClientInterface $client,
        private string $apiUrl,
        private LoggerInterface $logger,
        private CharacterRepository $characterRepository
    ) {
    }

    public static function getGroups(): array
    {
        return ['group3'];
    }

    public function load(ObjectManager $manager): void
    {
        $page = 1;
        $totalPage = 0;

        do {
            try {
                $response = $this->client->request('GET', $this->apiUrl . 'episode?page=' . $page);

                $data = $response->toArray();

                $info = $data['info'] ?? null;

                if (!is_null($info)) {
                    $totalPage = $info['pages'] ?? 0;
                }

                $results = $data['results'] ?? [];

                foreach ($results as $row) {
                    $episode = new Episode();
                    $episode->setName($row['name']);
                    $episode->setAirDate($row['air_date']);
                    $episode->setCode($row['episode']);
                    $episode->setUrl($row['url']);
                    $manager->persist($episode);
                    $manager->flush();

                    foreach ($row['characters'] as $url) {
                        $character = $this->characterRepository->findOneBy(['url' => $url]);

                        if ($character) {
                            $episodeCharacter = new EpisodeCharacter();
                            $episodeCharacter->setEpisode($episode);
                            $episodeCharacter->setCharacter($character);
                            $manager->persist($episodeCharacter);
                            $manager->flush();
                        }
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
