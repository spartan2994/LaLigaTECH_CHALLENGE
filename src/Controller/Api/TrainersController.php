<?php

namespace App\Controller\Api;


use FOS\RestBundle\Controller\Annotations as Rest;
use App\Service\TrainerRequestProcessor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TrainersController extends AbstractController
{
    /**
     * @Rest\Get(path="trainers")
     *
     *
     */
    public function index(TrainerRequestProcessor $trainerRequestProcessor): JsonResponse
    {
        $response = $trainerRequestProcessor->findAllTrainersProcessor();
        return $response;
    }

    /**
     * @Rest\Post(path="create_trainer")
     *
     *
     */
    public function createTrainer(
        Request $request,
        TrainerRequestProcessor $trainerRequestProcessor
    ): JsonResponse {
        $response = $trainerRequestProcessor->createTrainerProcessor($request);
        return $response;
    }

    /**
     * @Rest\Delete(path="delete_trainer")
     *
     *
     */
    public function deleteTrainer(
        Request $request,
        TrainerRequestProcessor $trainerRequestProcessor
    ): JsonResponse {
        $response = $trainerRequestProcessor->deleteTrainerProcessor($request);
        return $response;
    }
}
