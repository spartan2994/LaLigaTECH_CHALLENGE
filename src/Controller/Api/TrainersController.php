<?php

namespace App\Controller\Api;


use FOS\RestBundle\Controller\Annotations as Rest;
use App\Form\Model\TrainerDto;
use App\Repository\TrainerRepository;
use App\Form\Type\TrainerFormType;
use App\Service\TrainerManager;
use App\Service\MailerService;
use App\Service\PlayerManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;

class TrainersController extends AbstractController
{
    /**
     * @Rest\Get(path="trainers")
     *
     *
     */
    //Show all trainers
    public function index(TrainerManager $trainerManager): JsonResponse
    {
        $Trainers = $trainerManager->findAll();
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
        TrainerManager $trainerManager,
        PlayerManager $playerManager,
        MailerService $mailerService
    ): JsonResponse {
        $trainerDto = new TrainerDto();
        //Creating form type to manage data fields
        $form = $this->createForm(TrainerFormType::class, $trainerDto);
        $form->handleRequest($request);

        //Getting the budget by club "id_club"
        $budget = $playerManager->getBudgetByClub(
            $trainerDto->salary,
            $trainerDto->id_club
        );

        //Validation form to create the new Trainer
        if ($form->isSubmitted() && $form->isValid()) {
            //Budget Validation
            if ($budget > $trainerDto->salary) {
                //Control and detect exceptions
                $mailer_response = $mailerService->sendMail($trainerDto);

                if ($mailer_response == true) {
                    return $this->json([
                        "code" => 200,
                        "message" => "Trainer successfully created",
                        "errors" => "",
                        "data" => $trainerDto,
                    ]);
                } else {
                    return $this->json([
                        "code" => 400,
                        "message" => "Validation Failed",
                        "errors" => $mailer_response->getMessage(),
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
        TrainerManager $trainerManager
    ): JsonResponse {
        $trainer_id = $request->get("id");
        $trainer = $trainerManager->find($trainer_id);

        if ($trainer) {
            try {
                $trainerManager->delete($trainer);
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
                    "errors" => $th->getMessage(),
                ]);
            }
        }
    }
}
