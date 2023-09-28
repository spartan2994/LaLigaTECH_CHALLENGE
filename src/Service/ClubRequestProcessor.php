<?php

namespace App\Service;


use App\Form\Model\ClubDto;
use App\Service\ClubManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Form\Type\ClubFormType;
use App\Constants\HttpRequestsConstants;

class ClubRequestProcessor extends AbstractController
{

    private $clubManager;
    private $httpRequestsConstants;

    public function __construct(ClubManager $clubManager, HttpRequestsConstants $httpRequestsConstants)
    {
        $this->clubManager = $clubManager;
        $this->httpRequestsConstants = $httpRequestsConstants;

    }


    public function createClubProcessor(Request $request){

        $clubDto = new ClubDto();
        $form = $this->createForm(ClubFormType::class, $clubDto);
         
        $form->handleRequest($request); 
       
        if ($form->isSubmitted() && $form->isValid()) {
            
         if ($clubDto) {
            $club_validator = $this->clubManager->findByClub($clubDto->name);
            if ($club_validator == null) {
                $club = $this->clubManager->create();
                $club->setName($clubDto->name);
                $club->setBudget($clubDto->budget);
                $this->clubManager->save($club);
                return $this->json([
                    "code" => $this->httpRequestsConstants::HTTP_OK,
                    "message" => "Club successfully created",
                    "errors" => "",
                    "data" => $club,
                ]);
            } else {
                return $this->json([
                    "code" => $this->httpRequestsConstants::HTTP_BAD_REQUEST,
                    "message" => "Validation Failed",
                    "errors" => "Club name duplicated",
                ]);
            }
        }

        return $this->json([
            "code" => $this->httpRequestsConstants::HTTP_BAD_REQUEST,
            "data" => $form,
        ]);

    }
}

public function findAllClubsProcessor(){
    $clubs = $this->clubManager->findAll();
    return $this->json([
        "data" => $clubs,
    ]);
}

public function setBudgetClubProcessor(Request $request){
    $club_dto = new ClubDto();
    $form = $this->createForm(ClubFormType::class, $club_dto);
    $form->handleRequest($request);
    $id = $request->get("id");
    $budget = $this->clubManager->findClubBudget($id);

    if ($form->isSubmitted()) {
        if ($budget == null) {
            return $this->json([
                "code" => $this->httpRequestsConstants::HTTP_OK,
                "message" => "Club does not exists",
                "errors" => "",
                "data" => $budget,
            ]);
        } else {
            try {
                $budget->setBudget($club_dto->budget);
                $this->clubManager->flush();
                return $this->json([
                    "code" => $this->httpRequestsConstants::HTTP_OK,
                    "message" => "Budget successfully updated",
                    "errors" => "",
                    "data" => $budget,
                ]);
            } catch (\Throwable $e) {
                return $this->json([
                    "code" => $this->httpRequestsConstants::HTTP_BAD_REQUEST,
                    "message" => "Validation Failed",
                    "errors" => $e->getMessage(),
                ]);
            }
        }
    }

    return $this->json([
        "data" => $form,
    ]);
}

   
}
