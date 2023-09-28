<?php

namespace App\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Service\ClubRequestProcessor;

use Psr\Log\LoggerInterface;

class ClubsController extends AbstractController
{
    /**
     * @Rest\Get(path="clubs")
     *
     *
     */

    public function index(
        ClubRequestProcessor $clubRequestProcessor
    ): JsonResponse {
        $response = $clubRequestProcessor->findAllClubsProcessor();
        return $response;
    }

    /**
     * @Rest\Post(path="create_club")
     *
     *
     */
    public function createClub(
        ClubRequestProcessor $clubRequestProcessor,
        Request $request
    ): JsonResponse {
        $response = $clubRequestProcessor->createClubProcessor($request);
        return $response;
    }

    /**
     * @Rest\Post(path="set_budget_club")
     *
     *
     */
    public function setBudgetClub(
        ClubRequestProcessor $clubRequestProcessor,
        Request $request
    ): JsonResponse {
        $response = $clubRequestProcessor->setBudgetClubProcessor($request);
        return $response;
    }
}
