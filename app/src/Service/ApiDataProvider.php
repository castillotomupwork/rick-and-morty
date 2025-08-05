<?php

namespace App\Service;

use App\Enum\Gender;
use App\Enum\Status;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiDataProvider implements DataProviderInterface
{
    /**
     * Initializes the data provider with HTTP client, cache, and logger.
     *
     * @param HttpClientInterface $httpClient
     * @param CacheItemPoolInterface $cache
     * @param string $apiUrl
     * @param LoggerInterface $logger
     */
    public function __construct(
        private HttpClientInterface $httpClient, 
        private CacheItemPoolInterface $cache,
        private string $apiUrl,
        private LoggerInterface $logger
    ) {
        $this->apiUrl = rtrim($apiUrl, '/');
    }

    /**
     * Returns a filtered, paginated list of characters from cache.
     * Filters include dimension, location, episode, status, species, and gender.
     *
     * @param array $params
     * @return array
     */
    public function getCharacters(array $params = []): array
    {
        $page = $params['page'] ?? 1;
        $limit = 20;

        $allowedFilters = [
            'dimension' => null,
            'location' => null,
            'episode' => null,
            'status' => array_map(fn($s) => $s->value, Status::cases()),
            'species' => null,
            'gender' => array_map(fn($g) => $g->value, Gender::cases()),
        ];

        $filters = [];

        foreach ($allowedFilters as $field => $options) {
            $value = $params[$field] ?? null;

            if ($value !== null && $value !== '') {
                $value = strtolower($value);
                if (is_array($options)) {
                    if (in_array($value, $options, true)) {
                        $filters[$field] = $value;
                    }
                } else {
                    $filters[$field] = $value;
                }
            }
        }

        $allCharacters = $this->getCacheCharacters();

        $filtered = array_filter($allCharacters, function ($character) use ($filters) {
            foreach ($filters as $key => $value) {
                if ($character[$key] instanceof Status) {
                    $status = $character[$key];
                    $character[$key] = $status->value;
                }

                if ($character[$key] instanceof Gender) {
                    $gender = $character[$key];
                    $character[$key] = $gender->value;
                }
                
                if (strtolower($character[$key]) !== $value) {
                    return false;
                }
            }
            return true;
        });

        $total = count($filtered);
        $pageTotal = (int) ceil($total / $limit);
        $offset = ($page - 1) * $limit;
        $characters = array_slice($filtered, $offset, $limit);

        foreach ($characters as &$character) {
            if ($character['status']) {
                $status = $character['status'];
                $character['status'] = $status->label();
            }

            if ($character['gender']) {
                $gender = $character['gender'];
                $character['gender'] = $gender->label();
            }
        }

        return [
            'characters' => $characters,
            'page' => $page,
            'pageTotal' => $pageTotal,
            'error' => null,
        ];
    }

    /**
     * Returns all characters from cache.
     * Fetches and caches data from the external API if not cached.
     *
     * @return array
     */
    public function getCacheCharacters(): array
    {
        $item = $this->cache->getItem('rick_and_morty_characters');

        if (!$item->isHit()) {
            try {
                $page = 1;
                $total = 0;
                $characters = [];

                do {
                    $response = $this->httpClient->request(
                        'GET', 
                        $this->apiUrl . '/character?page=' . $page
                    );

                    $data = $response->toArray();

                    $info = $data['info'] ?? null;

                    if (!is_null($info)) {
                        $total = $info['pages'] ?? 0;
                    }

                    $dataArray = array_map(function ($row) {
                        $status = Status::safeFrom($row['status']) ?? Status::UNKNOWN;

                        $gender = Gender::safeFrom($row['gender']) ?? Gender::UNKNOWN;

                        $dimension = '';
                        if (!empty($row['location']['url'])) {
                            $responseLocation = $this->httpClient->request('GET', $row['location']['url']);

                            $dataLocation = $responseLocation->toArray();

                            if ($dataLocation) {
                                $dimension = $dataLocation['dimension'];
                            }
                        }

                        $episode = '';
                        $last = count($row['episode']) - 1;
                        if ($last >= 0) {
                            $responseEpisode = $this->httpClient->request('GET', $row['episode'][$last]);

                            $dataEpisode = $responseEpisode->toArray();

                            if ($dataEpisode) {
                                $episode = $dataEpisode['name'];
                            }
                        }

                        return [
                            'name' => $row['name'],
                            'image' => $row['image'],
                            'species' => $row['species'],
                            'gender' => $gender,
                            'status' => $status,
                            'type' => $row['type'],
                            'origin' => $row['origin']['name'],
                            'dimension' => $dimension,
                            'location' => $row['location']['name'],
                            'episode' => $episode,
                        ];
                    }, $data['results']);

                    $characters = array_merge($characters, $dataArray);

                    $page++;

                } while ($page <= $total);

                $item->set($characters);
                $item->expiresAfter(86400);
                
                $this->cache->save($item);
                
            } catch (\Exception $e) {

                $this->logger->error($e->getMessage());

                return [];
            }
        }

        return $item->get();
    }

    /**
     * Returns a list of unique dimensions from the external API.
     * Uses caching to avoid repeated HTTP calls.
     *
     * @return array
     */
    public function getDimensions(): array
    {
        $item = $this->cache->getItem('rick_and_morty_dimensions');

        if (!$item->isHit()) {
            try {
                $page = 1;
                $total = 0;
                $dataArray = [];
                $dimensions = [];

                do {
                    $response = $this->httpClient->request(
                        'GET', 
                        $this->apiUrl . '/location?page=' . $page
                    );

                    $data = $response->toArray();

                    $info = $data['info'] ?? null;

                    if (!is_null($info)) {
                        $total = $info['pages'] ?? 0;
                    }

                    foreach ($data['results'] as $row) {
                        if (!in_array($row['dimension'], $dataArray)) {
                            $dataArray[] = $row['dimension'];
                        }
                    }

                    $page++;

                } while ($page <= $total);

                sort($dataArray);

                foreach ($dataArray as $value) {
                    $dimensions[] = [
                        'id' => $value,
                        'name' => $value,
                    ];
                }

                $item->set($dimensions);
                $item->expiresAfter(86400);
                
                $this->cache->save($item);

            } catch (\Exception $e) {

                $this->logger->error($e->getMessage());

                return [];
            }
        }

        return $item->get();
    }

    /**
     * Returns a list of unique location names from the external API.
     * Uses caching to avoid repeated HTTP calls.
     *
     * @return array
     */
    public function getLocations(): array
    {
        $item = $this->cache->getItem('rick_and_morty_locations');

        if (!$item->isHit()) {
            try {
                $page = 1;
                $total = 0;
                $dataArray = [];
                $locations = [];

                do {
                    $response = $this->httpClient->request(
                        'GET', 
                        $this->apiUrl . '/location?page=' . $page
                    );

                    $data = $response->toArray();

                    $info = $data['info'] ?? null;

                    if (!is_null($info)) {
                        $total = $info['pages'] ?? 0;
                    }

                    foreach ($data['results'] as $row) {
                        if (!in_array($row['name'], $dataArray)) {
                            $dataArray[] = $row['name'];
                        }
                    }

                    $page++;

                } while ($page <= $total);

                sort($dataArray);

                foreach ($dataArray as $value) {
                    $locations[] = [
                        'id' => $value,
                        'name' => $value,
                    ];
                }

                $item->set($locations);
                $item->expiresAfter(86400);
                
                $this->cache->save($item);

            } catch (\Exception $e) {

                $this->logger->error($e->getMessage());

                return [];
            }
        }

        return $item->get();
    }

    /**
     * Returns a list of unique episode names from the external API.
     * Uses caching to avoid repeated HTTP calls.
     *
     * @return array
     */
    public function getEpisodes(): array
    {
        $item = $this->cache->getItem('rick_and_morty_episodes');

        if (!$item->isHit()) {
            try {
                $page = 1;
                $total = 0;
                $dataArray = [];
                $episodes = [];

                do {
                    $response = $this->httpClient->request(
                        'GET', 
                        $this->apiUrl . '/episode?page=' . $page
                    );

                    $data = $response->toArray();

                    $info = $data['info'] ?? null;

                    if (!is_null($info)) {
                        $total = $info['pages'] ?? 0;
                    }

                    foreach ($data['results'] as $row) {
                        if (!in_array($row['name'], $dataArray)) {
                            $dataArray[] = $row['name'];
                        }
                    }

                    $page++;

                } while ($page <= $total);

                sort($dataArray);

                foreach ($dataArray as $value) {
                    $episodes[] = [
                        'id' => $value,
                        'name' => $value,
                    ];
                }

                $item->set($episodes);
                $item->expiresAfter(86400);
                
                $this->cache->save($item);

            } catch (\Exception $e) {

                $this->logger->error($e->getMessage());

                return [];
            }
        }

        return $item->get();
    }
}