<?php

namespace App\Service;

use App\Entity\Trainer;
use App\Repository\TrainerRepository;
use Doctrine\ORM\EntityManagerInterface;


class TrainerManager
{
    private $em;
    private $trainerRepository;

    public function __construct(EntityManagerInterface $em, TrainerRepository $trainerRepository)
    {
        $this->em = $em;
        $this->trainerRepository = $trainerRepository;
    }
    public function save(Trainer $trainer): Trainer
    {
        $this->em->persist($trainer);
        $this->em->flush();
        return $trainer;
    }


    public function create(): Trainer
    {
        $trainer = new Trainer;
        return $trainer;
    }


    public function findAll()
    {
        $trainers = $this->em->getRepository(Trainer::class)->findAll();
        return $trainers;
    }

    public function delete($trainer_id)
    {
        $this->em->remove($trainer_id);
        $this->em->flush();
    }

    public function find($trainer_id)
    {
        $trainer = $this->em->getRepository(Trainer::class)->find($trainer_id);
        return $trainer;
    }
}
