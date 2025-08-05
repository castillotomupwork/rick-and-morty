<?php

namespace App\Controller;

use App\Enum\Gender;
use App\Enum\Status;
use App\Helper\ParseHelper;
use App\Service\DataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CharactersController extends AbstractController
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
     * Displays the character index page.
     *
     * @param ParameterBagInterface $parameterBag
     * @return Response
     */
    #[Route('/', name: 'characters_index')]
    public function index(ParameterBagInterface $parameterBag): Response
    {
        return $this->render('characters/index.html.twig', [
            'statuses' => Status::cases(),
            'genders' => Gender::cases(),
            'data_provider_type' => $parameterBag->get('data_provider_type')
        ]);
    }

    /**
     * Returns a list of characters in JSON format.
     *
     * @param Request $request
     * @param ParseHelper $parseHelper
     * @param ParameterBagInterface $parameterBag
     * @return JsonResponse
     */
    #[Route('/characters', name: 'characters_data')]
    public function getCharacters(
        Request $request, 
        ParseHelper $parseHelper,
        ParameterBagInterface $parameterBag
    ): JsonResponse {
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

        if ($parameterBag->get('data_provider_type') == 'db') {
            $param = $parseHelper->requestToInteger(
                $request->query->all(), 
                ['dimension', 'location', 'episode']
            );
        } else {
            $param = $request->query->all();
        }

        $page = $param['page'];

        $data = $this->dataService->characters($param);

        $page = $data['page'] ?? 1;
        $pageTotal = $data['pageTotal'] ?? 0;
        $characters = $data['characters'] ?? [];
        $error = $data['error'] ?? null;

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
        
        if ($error) {
            return $this->json([
                'error' => $error,
                'pagination' => $pagination,
            ]);
        }

        return $this->json([
            'characters' => $characters,
            'pagination' => $pagination,
        ]);
    }
}
