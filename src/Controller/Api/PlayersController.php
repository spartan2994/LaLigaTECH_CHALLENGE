<?php

namespace App\Controller\Api;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Entity\Player;
use App\Form\Model\PlayerDto;
use App\Entity\Trainer;
use App\Entity\Club;
use App\Service\PlayerManager;
use App\Form\Type\PlayerFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class PlayersController extends AbstractController
{
    /**
     * @Rest\Get(path="players")
     *
     *
     */
    //Show all players
    public function index(PlayerManager $playerManager): JsonResponse
    {
        $Players = $playerManager->findAll();
        return $this->json([
            "data" => $Players,
        ]);
    }

    /**
     * @Rest\Get(path="find_players")
     *
     *
     */

    //List players from a club with the possibility of filtering by one of the properties (for example name) and with pagination
    public function findPlayerClub(
        PlayerManager $playerManager,
        Request $request
    ): JsonResponse {
        //Getting params
        $pag = $request->get("pag");
        $id_club = $request->get("id_club");
        $name = $request->get("player");
        //Getting Club Players
        $players_club = $playerManager->findByPlayerClub($id_club, $name, $pag);
        return $this->json([
            "code" => 200,
            "message" => "",
            "errors" => "",
            "data" => $players_club,
        ]);
    }

    /**
     * @Rest\Post(path="create_player")
     *
     *
     */
    public function createPlayer(
        Request $request,
        PlayerManager $playerManager
    ): JsonResponse {
        $playerDto = new PlayerDto();

        //Creating form type to manage data fields
        $form = $this->createForm(PlayerFormType::class, $playerDto);
        $form->handleRequest($request);

        //Getting the budget by club "id_club"
        $budget = $playerManager->getBudgetByClub(
            $playerDto->salary,
            $playerDto->id_club
        );

        //Validation form to create the new Player
        if ($form->isSubmitted() && $form->isValid()) {
            if ($budget > $playerDto->salary) {
                //Control and detect exceptions
                try {
                    $player = $playerManager->create();
                    $player->setIdClub($playerDto->id_club);
                    $player->setName($playerDto->name);
                    $player->setSalary($playerDto->salary);
                    $player->setEmail($playerDto->email);
                    //Save object to DB
                    $playerManager->save($player);
                    //Returnning json response with status
                    return $this->json([
                        "code" => 200,
                        "message" => "Player successfully created",
                        "errors" => "",
                        "data" => $player,
                    ]);
                    //Returnnig exception with json response message
                } catch (\Exception $e) {
                    return $this->json([
                        "code" => 400,
                        "message" => "Validation Failed",
                        "errors" => $e->getMessage(),
                    ]);
                }
            } else {
                return $this->json([
                    "code" => 400,
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

    /**
     * @Rest\Delete(path="delete_player")
     *
     *
     */
    public function deletePlayer(
        Request $request,
        PlayerManager $playerManager
    ): JsonResponse {
        $player_id = $request->get("id");
        $player = $playerManager->find($player_id);

        if ($player) {
            try {
                $playerManager->delete($player);
                return $this->json([
                    "code" => 200,
                    "message" => "Player successfully deleted",
                    "errors" => "",
                    "data" => $player,
                ]);
            } catch (\Throwable $th) {
                return $this->json([
                    "code" => 400,
                    "message" => "Validation Failed",
                    "errors" => $th->getMessage(),
                ]);
            }
        }
    }
}
