<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;


final class CharactersController extends AbstractController
{
    public function __construct(private readonly HttpClientInterface $client)
    {

    }

    #[Route('/', name: 'characters_index')]
    public function index(LoggerInterface $logger): Response
    {
        $apiUrl = $this->getParameter('api_url') . "/character";

        try {
            $response = $this->client->request('GET', $apiUrl);

            $data = $response->toArray();

            $characters = [];
            foreach ($data as $row) {
                $characters[] = [
                    'id' => '',
                    'name' => '',
                    'status' => '',
                    'species' => '',
                    'type' => '',
                    'gender' => '',
                    'id' => '',
                    'id' => '',
                    'id' => '',
                ];
            }
        } catch (\Exception $e) {
            $logger->error($e->getMessage());
        }

        return $this->render('characters/index.html.twig');
    }

    #[Route('/characters', name: 'characters_data')]
    public function getCharacters(
        Request $request, 
        LoggerInterface $logger
    ): JsonResponse {
        $page = $request->query->getInt('page', 1);

        $allowedFilters = [
            'status' => ['alive', 'dead', 'unknown'],
            'gender' => ['female', 'male', 'genderless', 'unknown'],
            'species' => null,
            // add more filters as needed
        ];

        $queryParams = ['page' => $page];

        foreach ($allowedFilters as $filter => $validOptions) {
            $value = $request->query->get($filter);

            if ($value !== null && $value !== '') {
                if (is_array($validOptions)) {
                    if (in_array(strtolower($value), $validOptions, true)) {
                        $queryParams[$filter] = strtolower($value);
                    }
                } else {
                    $queryParams[$filter] = $value;
                }
            }
        }

        $apiUrl = $this->getParameter('api_url') . "/character?" . http_build_query($queryParams);

        $pagination = [
            'prev' => 0, 
            'next' => 0,
            'first' => 0,
            'last' => 0,
            'pageLabel' => null,
            'disablePrev' => true,
            'disableNext' => true,
            'disableFirst' => true,
            'disableLast' => true,
        ];

        try {
            $response = $this->client->request('GET', $apiUrl);

            $data = $response->toArray();

            $pageTotal = $data['info']['pages'];

            if ($page <= $pageTotal && $page > 1) {
                $pagination['prev'] = $page - 1;
                $pagination['first'] = 1;
                $pagination['disablePrev'] = false;
                $pagination['disableFirst'] = false;
            }

            if ($pageTotal > 1 && $page != $pageTotal) {
                $pagination['next'] = $page + 1;
                $pagination['last'] = $pageTotal;
                $pagination['disableNext'] = false;
                $pagination['disableLast'] = false;
            }

            if ($pageTotal > 0) {
                $pagination['pageLabel'] = $page . " of " . $pageTotal;
            }

            return $this->json([
                'characters' => $data['results'],
                'pagination' => $pagination,
            ]);

        } catch (\Exception $e) {

            $logger->error($e->getMessage());

            return $this->json([
                'characters' => [],
                'pagination' => $pagination,
                'error' => 'No characters found.',
            ]);
        }
    }

    #[Route('/dimensions', name: 'dimensions_data')]
    public function getDimensions(): JsonResponse
    {
        $apiUrl = $this->getParameter('api_url') . "/location";

        try {
            $response = $this->client->request('GET', $apiUrl);

            $data = $response->toArray();

            $dimensions = [];
            foreach ($data['results'] as $location) {
                if (!in_array($location['dimension'], $dimensions)) {
                    $dimensions[] = $location['dimension'];
                }
            }

            sort($dimensions);

            return $this->json($dimensions);

        } catch (\Exception $e) {
            return $this->json([]);
        }
    }
}
