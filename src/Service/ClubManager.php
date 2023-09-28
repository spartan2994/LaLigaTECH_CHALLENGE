<?php

namespace App\Service;

use App\Entity\Club;
use App\Form\Model\ClubDto;
use App\Repository\ClubRepository;
use Doctrine\ORM\EntityManagerInterface;

class ClubManager
{
    private $em;
    private $clubRepository;

    public function __construct(
        EntityManagerInterface $em,
        ClubRepository $clubRepository
    ) {
        $this->em = $em;
        $this->clubRepository = $clubRepository;
    }

    public function create(): Club
    {
        $club = new Club();
        return $club;
    }

    public function findClubBudget($club_id)
    {
        $club_budget = $this->em->getRepository(Club::class)->find($club_id);
        return $club_budget;
    }

    public function findByClub($club_name)
    {
        $club = $this->em->getRepository(Club::class)->findByClub($club_name);
        return $club;
    }

    public function findAll()
    {
        $clubs = $this->em->getRepository(Club::class)->findAll();
        return $clubs;
    }

    public function save(Club $club): Club
    {
        $this->em->persist($club);
        $this->em->flush();
        return $club;
    }

    public function reload(Club $club): Club
    {
        $this->em->refresh($club);
        return $club;
    }

    public function flush()
    {
        $this->em->flush();
    }
}
