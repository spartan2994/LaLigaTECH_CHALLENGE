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

    //List players from a club with the possibility of filtering by one of the properties (for example name) and with pagination
    public function findPlayerClub(
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {
        //New Player object
        $player = new Player();

        //Getting params
        $player_club = $request->get("club");
        $player_name = $request->get("player");
        $pag = $request->get("pag");

        //Creating form type to manage data fields
        $form = $this->createForm(PlayerFormType::class, $player);
        $form->handleRequest($request);

        //Getting Club Players
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

        //Getting params
        $player_id_club = $request->get("id_club");
        $player_name = $request->get("name");
        $player_salary = $request->get("salary");
        $player_email = $request->get("email");

        //Creating form type to manage data fields
        $form = $this->createForm(PlayerFormType::class, $player);
        $form->handleRequest($request);

        //Getting the budget by club "id_club"
        $budget = $em
            ->getRepository(Club::class)
            ->getBudgetByClub($player_salary, $player_id_club);

        //Validation form to create the new Player
        if ($form->isSubmitted() && $form->isValid()) {
            if ($budget > $player_salary) {
                //Control and detect exceptions
                try {
                    //Config params and data to send Email

                    // $email = (new Email())
                    //     ->from("llt@test.com")
                    //     ->to($player_email)
                    //     //->cc('cc@example.com')
                    //     //->bcc('bcc@example.com')
                    //     //->replyTo('fabien@example.com')
                    //     //->priority(Email::PRIORITY_HIGH)
                    //     ->subject(
                    //         "Your Account Has Been Created - LaLiga TECH!"
                    //     )
                    //     ->html(
                    //         '<h5 class="card-title">Welcome ' .
                    //             $player_name .
                    //             "!</h5>"
                    //     );
                    //     $mailer->send($email);

                    //Start to manage object
                    $em->persist($player);
                    //Save object to DB
                    $em->flush();
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
