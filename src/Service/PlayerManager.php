<?php

namespace App\Service;

use App\Entity\Club;
use App\Entity\Player;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;


class PlayerManager 
{
    private $em;
    private $playerRepository;

    public function __construct(EntityManagerInterface $em, PlayerRepository $playerRepository)
    {
        $this->em = $em;
        $this->playerRepository = $playerRepository;

    }

    public function create(): Player
    {
        $player = new Player;
        return $player;
    }

    public function save(Player $player): Player
    {
        $this->em->persist($player);
        $this->em->flush();
        return $player;
    }

    public function findAll(){
        $players = $this->em->getRepository(Player::class)->findAll();
        return $players;
    }

    public function findByPlayerClub($id_club, $name, $pag)
    {
        $players_club = $this->em->getRepository(Player::class)->findByPlayerClub($id_club ,$name, $pag);
        return $players_club;
    }


    public function getBudgetByClub($salary, $id_club)
    {
        $budget = $this->em->getRepository(Club::class)->getBudgetByClub($salary, $id_club);
        return $budget;
    }

    public function find($player_id){
        $player = $this->em->getRepository(Player::class)->find($player_id);
        return $player;
    }

    public function delete($player_id){
        $this->em->remove($player_id);
        $this->em->flush();
    }


   
}
