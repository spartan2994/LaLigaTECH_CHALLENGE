<?php

namespace App\Service;

use App\Form\Model\PlayerDto;
use App\Service\PlayerManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Form\Type\PlayerFormType;
use App\Constants\HttpRequestsConstants;

class PlayerRequestProcessor extends AbstractController
{
    private $playerManager;
    private $httpRequestsConstants;

    public function __construct(PlayerManager $playerManager, HttpRequestsConstants $httpRequestsConstants)
    {
        $this->playerManager = $playerManager;
        $this->httpRequestsConstants = $httpRequestsConstants;
    }

    public function findAllPlayersProcessor()
    {
        $Players = $this->playerManager->findAll();
        return $this->json([
            "data" => $Players,
        ]);
    }

    public function findPlayerClubProcessor(Request $request)
    {
        $pag = $request->get("pag");
        $id_club = $request->get("id_club");
        $name = $request->get("player");
        $players_club = $this->playerManager->findByPlayerClub(
            $id_club,
            $name,
            $pag
        );
        if (!$players_club) {
            return $this->json([
                "code" => $this->httpRequestsConstants::HTTP_OK,
                "message" => "Not found",
                "errors" => "",
            ]);
        }
        return $this->json([
            "code" => $this->httpRequestsConstants::HTTP_OK,
            "message" => "",
            "errors" => "",
            "data" => $players_club,
        ]);
    }

    public function createPlayerProcessor(Request $request)
    {
        $playerDto = new PlayerDto();
        $form = $this->createForm(PlayerFormType::class, $playerDto);
        $form->handleRequest($request);
        $budget = $this->playerManager->getBudgetByClub(
            $playerDto->salary,
            $playerDto->id_club
        );

        if ($form->isSubmitted() && $form->isValid()) {
            if ($budget > $playerDto->salary) {
                try {
                    $player = $this->playerManager->create();
                    $player->setIdClub($playerDto->id_club);
                    $player->setName($playerDto->name);
                    $player->setSalary($playerDto->salary);
                    $player->setEmail($playerDto->email);
                    $this->playerManager->save($player);
                    return $this->json([
                        "code" => $this->httpRequestsConstants::HTTP_OK,
                        "message" => "Player successfully created",
                        "errors" => "",
                        "data" => $player,
                    ]);
                } catch (\Exception $e) {
                    return $this->json([
                        "code" => $this->httpRequestsConstants::HTTP_BAD_REQUEST,
                        "message" => "Validation Failed",
                        "errors" => $e->getMessage(),
                    ]);
                }
            } else {
                return $this->json([
                    "code" => $this->httpRequestsConstants::HTTP_BAD_REQUEST,
                    "message" => "Validation Failed",
                    "errors" =>
                    'The club no longer has a budget ($' . $budget . ")",
                ]);
            }
        }

        return $this->json([
            "data" => $form,
        ]);
    }

    public function deletePlayerProcessor(Request $request)
    {
        $player_id = $request->get("id");
        $player = $this->playerManager->find($player_id);

        if ($player) {
            try {
                $this->playerManager->delete($player);
                return $this->json([
                    "code" => $this->httpRequestsConstants::HTTP_OK,
                    "message" => "Player successfully deleted",
                    "errors" => "",
                    "data" => $player,
                ]);
            } catch (\Throwable $th) {
                return $this->json([
                    "code" => $this->httpRequestsConstants::HTTP_BAD_REQUEST,
                    "message" => "Validation Failed",
                    "errors" => $th->getMessage(),
                ]);
            }
        }
    }
}
