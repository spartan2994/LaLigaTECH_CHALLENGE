<?php

namespace App\Controller\Api;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Entity\Club;
use App\Repository\ClubRepository;
use App\Form\Type\ClubFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

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
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {
        $club = new Club();
        $club_name = $request->get("name");
        $form = $this->createForm(ClubFormType::class, $club);
        $form->handleRequest($request);
        $club_validator = $em->getRepository(Club::class)->findByClub($club_name);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($club_validator == null) {
                $em->persist($club);
                $em->flush();
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
            "data" => $form,
        ]);
    }

    /**
     * @Rest\Post(path="set_budget_club")
     *
     *
     */
    public function setBudgetClub(
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {
        $club = new Club();
        $club_name = $request->get("name");
        $club_budget = $request->get("budget");
        $club_id = $request->get("id");
        $form = $this->createForm(ClubFormType::class, $club);
        $form->handleRequest($request);
        $budget = $em->getRepository(Club::class)->find($club_id);

        if ($form->isSubmitted()) {
            if ($club_budget == null) {
                return $this->json([
                    "code" => 200,
                    "message" => "Club does not exists",
                    "errors" => "",
                    "data" => $club_budget,
                ]);
            } else {
                try {
                    $budget->setBudget($club_budget);
                    $em->flush();
                    return $this->json([
                        "code" => 200,
                        "message" => "Budget successfully updated",
                        "errors" => "",
                        "data" => $club_budget,
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
