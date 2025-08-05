<?php

namespace App\Controller;

use App\Service\DataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class DimensionsController extends AbstractController
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
     * Returns a list of dimensions in JSON format.
     *
     * @return JsonResponse
     */
    #[Route('/dimensions', name: 'dimensions_data')]
    public function getDimensions(): JsonResponse
    {
        return $this->json($this->dataService->dimensions());
    }
}
