<?php

namespace App\Service;

use App\Form\Model\TrainerDto;
use App\Service\TrainerManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Form\Type\TrainerFormType;
use App\Service\MailerService;
use App\Constants\HttpRequestsConstants;

class TrainerRequestProcessor extends AbstractController
{
    private $trainerManager;
    private $playerManager;
    private $mailerService;
    private $httpRequestsConstants;

    public function __construct(
        TrainerManager $trainerManager,
        PlayerManager $playerManager,
        MailerService $mailerService,
        HttpRequestsConstants $httpRequestsConstants
    ) {
        $this->trainerManager = $trainerManager;
        $this->playerManager = $playerManager;
        $this->mailerService = $mailerService;
        $this->httpRequestsConstants = $httpRequestsConstants;
    }

    public function findAllTrainersProcessor()
    {
        $Trainers = $this->trainerManager->findAll();
        return $this->json([
            "data" => $Trainers,
        ]);
    }

    public function createTrainerProcessor(Request $request)
    {
        $trainerDto = new TrainerDto();

        $form = $this->createForm(TrainerFormType::class, $trainerDto);
        $form->handleRequest($request);
        $budget = $this->playerManager->getBudgetByClub(
            $trainerDto->salary,
            $trainerDto->id_club
        );

        if ($form->isSubmitted() && $form->isValid()) {
            if ($budget > $trainerDto->salary) {
                $mailer_response = $this->mailerService->sendMail($trainerDto);

                if ($mailer_response == true) {
                    return $this->json([
                        "code" => $this->httpRequestsConstants::HTTP_OK,
                        "message" => "Trainer successfully created",
                        "errors" => "",
                        "data" => $trainerDto,
                    ]);
                } else {
                    return $this->json([
                        "code" => $this->httpRequestsConstants::HTTP_BAD_REQUEST,
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

    public function deleteTrainerProcessor(Request $request)
    {
        $trainer_id = $request->get("id");
        $trainer = $this->trainerManager->find($trainer_id);
        if ($trainer) {
            try {
                $this->trainerManager->delete($trainer);
                return $this->json([
                    "code" => $this->httpRequestsConstants::HTTP_OK,
                    "message" => "Trainer successfully deleted",
                    "errors" => "",
                    "data" => $trainer,
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
