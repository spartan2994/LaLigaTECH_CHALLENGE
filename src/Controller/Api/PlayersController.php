<?php

namespace App\Controller\Api;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Entity\Player;
use App\Entity\Trainer;
use App\Entity\Club;
use App\Repository\PlayerRepository;
use App\Form\Type\PlayerFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    public function index(
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {
        $Players = $em->getRepository(Player::class)->findAll();
        return $this->json([
            "data" => $Players,
        ]);
    }

    /**
     * @Rest\Get(path="find_players")
     *
     *
     */
    public function findPlayerClub(
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {
        $player = new Player();
        $player_club = $request->get("club");
        $player_name = $request->get("player");
        $pag = $request->get("pag");
        $form = $this->createForm(PlayerFormType::class, $player);
        $form->handleRequest($request);
        $players_club = $em
            ->getRepository(Player::class)
            ->findByPlayerClub($player_club, $player_name, $pag);
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
        EntityManagerInterface $em
    ): JsonResponse {
        $player = new Player();
        $player_id_club = $request->get("id_club");
        $player_name = $request->get("name");
        $player_salary = $request->get("salary");
        $player_email = $request->get("email");
        $form = $this->createForm(PlayerFormType::class, $player);
        $form->handleRequest($request);
        $budget = $em->getRepository(Club::class)
        ->getBudgetByClub($player_salary, $player_id_club);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($budget > $player_salary) {
                try {
                    $em->persist($player);
                    $em->flush();
                    return $this->json([
                        "code" => 200,
                        "message" => "Player successfully created",
                        "errors" => "",
                        "data" => $player,
                    ]);
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
        EntityManagerInterface $em
    ): JsonResponse {
        $player = new Player();
        $player_id = $request->get("id");
        $form = $this->createForm(PlayerFormType::class, $player);
        $form->handleRequest($request);
        $player = $em->getRepository(Player::class)->find($player_id);

        if ($player) {
            try {
                $em->remove($player);
                $em->flush();
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
                    "errors" => $e->getMessage(),
                ]);
            }
        }

        return $this->json([
            "data" => $form,
        ]);
    }
}
