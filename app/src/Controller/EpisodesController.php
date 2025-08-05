<?php

namespace App\Controller;

use App\Service\DataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class EpisodesController extends AbstractController
{
    /**
     * Class constructor to initialize data service dependency.
     *
     * @param DataService $dataService
     */
    public function __construct(private DataService $dataService) 
    {
        
    }

    /**
     * Returns a list of episodes in JSON format.
     *
     * @return JsonResponse
     */
    #[Route('/episodes', name: 'episodes_data')]
    public function getEpisodes(): JsonResponse
    {
        return $this->json($this->dataService->episodes());
    }
}
