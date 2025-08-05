<?php

namespace App\Controller;

use App\Service\DataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LocationsController extends AbstractController
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
     * Returns a list of locations in JSON format.
     *
     * @return JsonResponse
     */
    #[Route('/locations', name: 'locations_data')]
    public function getLocations(): Response
    {
       return $this->json($this->dataService->locations());
    }
}
