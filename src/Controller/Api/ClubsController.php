<?php

namespace App\Controller\Api;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Service\ClubFormProcessor;
use App\Service\ClubManager;
use App\Entity\Club;
use App\Repository\ClubRepository;
use App\Form\Type\ClubFormType;
use App\Form\Model\ClubDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Psr\Log\LoggerInterface;

class ClubsController extends AbstractController
{
    /**
     * @Rest\Get(path="clubs")
     *
     *
     */

    public function index(
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {
        $clubs = $em->getRepository(Club::class)->findAll();
        return $this->json([
            "data" => $clubs,
        ]);
    }

    /**
     * @Rest\Post(path="create_club")
     *
     *
     */
    public function createClub(
        ClubManager $clubManager,
        Request $request,
        ClubFormProcessor $bookFromProcessor
    ): JsonResponse {
        $clubDto = new ClubDto();
        $form = $this->createForm(ClubFormType::class, $clubDto);
        $club_Dto = $bookFromProcessor($clubDto, $request, $form, $clubManager);

        if ($club_Dto) {
            $club_validator = $clubManager->findByClub($club_Dto->name);
            if ($club_validator == null) {
                $club = $clubManager->create();
                $club->setName($club_Dto->name);
                $club->setBudget($club_Dto->budget);
                $clubManager->save($club);
                return $this->json([
                    "code" => 200,
                    "message" => "Club successfully created",
                    "errors" => "",
                    "data" => $club,
                ]);
            } else {
                return $this->json([
                    "code" => 400,
                    "message" => "Validation Failed",
                    "errors" => "Club name duplicated",
                ]);
            }
        }

        return $this->json([
            "code" => 400,
            "data" => $form,
        ]);
    }

    /**
     * @Rest\Post(path="set_budget_club")
     *
     *
     */
    public function setBudgetClub(
        ClubManager $clubManager,
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {
        
        $club_dto = new ClubDto();
        $form = $this->createForm(ClubFormType::class, $club_dto);
        $form->handleRequest($request);
        $id = $request->get("id");
        $budget = $clubManager->findClubBudget($id);

        if ($form->isSubmitted()) {
            if ($club_dto->budget == null) {
                return $this->json([
                    "code" => 200,
                    "message" => "Club does not exists",
                    "errors" => "",
                    "data" => $budget,
                ]);
            } else {
                try {
                    $budget->setBudget($club_dto->budget);
                    $clubManager->flush();
                    return $this->json([
                        "code" => 200,
                        "message" => "Budget successfully updated",
                        "errors" => "",
                        "data" => $budget,
                    ]);
                } catch (\Throwable $e) {
                    return $this->json([
                        "code" => 400,
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
