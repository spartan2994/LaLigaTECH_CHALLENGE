<?php

namespace App\Controller\Api;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Entity\Trainer;
use App\Entity\Player;
use App\Entity\Club;
use App\Repository\TrainerRepository;
use App\Form\Type\TrainerFormType;
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

class TrainersController extends AbstractController
{
    /**
     * @Rest\Get(path="trainers")
     *
     *
     */
    //Show all trainers
    public function index(
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {
        $Trainers = $em->getRepository(Trainer::class)->findAll();
        return $this->json([
            "data" => $Trainers,
        ]);
    }

    /**
     * @Rest\Post(path="create_trainer")
     *
     *
     */
    public function createTrainer(
        MailerInterface $mailer,
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {
        $trainer = new Trainer();

        //Getting params
        $trainer_salary = $request->get("salary");
        $trainer_id_club = $request->get("id_club");
        $trainer_name = $request->get("name");
        $trainer_email = $request->get("email");

        //Creating form type to manage data fields
        $form = $this->createForm(TrainerFormType::class, $trainer);
        $form->handleRequest($request);

        //Getting the budget by club "id_club"
        $budget = $em
            ->getRepository(Club::class)
            ->getBudgetByClub($trainer_salary, $trainer_id_club);

        //Validation form to create the new Trainer
        if ($form->isSubmitted() && $form->isValid()) {
            //Budget Validation
            if ($budget > $trainer_salary) {
                //Control and detect exceptions
                try {
                    //Config params and data to send Email
                    $email = (new Email())
                        ->from("llt@test.com")
                        ->to($trainer_email)
                        //->cc('cc@example.com')
                        //->bcc('bcc@example.com')
                        //->replyTo('fabien@example.com')
                        //->priority(Email::PRIORITY_HIGH)
                        ->subject(
                            "Your Account Has Been Created - LaLiga TECH!"
                        )
                        ->html(
                            '<h5 class="card-title">Welcome ' .
                                $trainer_name .
                                "!</h5>"
                        );
                    $mailer->send($email);

                    //Start to manage object
                    $em->persist($trainer);
                    //Save object to DB
                    $em->flush();

                    //Returnning json response with status
                    return $this->json([
                        "code" => 200,
                        "message" => "Trainer successfully created",
                        "errors" => "",
                        "data" => $trainer,
                    ]);
                } catch (\Exception $e) {
                    //Returnnig exception with json response message
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
     * @Rest\Delete(path="delete_trainer")
     *
     *
     */
    public function deleteTrainer(
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {
        $trainer = new Trainer();
        $trainer_id = $request->get("id");
        $form = $this->createForm(TrainerFormType::class, $trainer);
        $form->handleRequest($request);
        $trainer = $em->getRepository(Trainer::class)->find($trainer_id);

        if ($trainer) {
            try {
                $em->remove($trainer);
                $em->flush();
                return $this->json([
                    "code" => 200,
                    "message" => "Trainer successfully deleted",
                    "errors" => "",
                    "data" => $trainer,
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
