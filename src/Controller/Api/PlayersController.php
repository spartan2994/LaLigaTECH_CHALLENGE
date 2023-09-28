<?php

namespace App\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use App\Form\Model\PlayerDto;
use App\Service\PlayerManager;
use App\Form\Type\PlayerFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Service\PlayerRequestProcessor;

class PlayersController extends AbstractController
{
    /**
     * @Rest\Get(path="players")
     *
     *
     */
    public function index(
        PlayerRequestProcessor $playerRequestProcessor
    ): JsonResponse {
        $response = $playerRequestProcessor->findAllPlayersProcessor();
        return $response;
    }

    /**
     * @Rest\Get(path="find_players")
     *
     *
     */

    public function findPlayerClub(
        Request $request,
        PlayerRequestProcessor $playerRequestProcessor
    ): JsonResponse {
        $response = $playerRequestProcessor->findPlayerClubProcessor($request);
        return $response;
    }

    /**
     * @Rest\Post(path="create_player")
     *
     *
     */
    public function createPlayer(
        Request $request,
        PlayerRequestProcessor $playerRequestProcessor
    ): JsonResponse {
        $response = $playerRequestProcessor->createPlayerProcessor($request);
        return $response;
    }

    /**
     * @Rest\Delete(path="delete_player")
     *
     *
     */
    public function deletePlayer(
        Request $request,
        PlayerRequestProcessor $playerRequestProcessor
    ): JsonResponse {
        $response = $playerRequestProcessor->deletePlayerProcessor($request);
        return $response;
    }
}
