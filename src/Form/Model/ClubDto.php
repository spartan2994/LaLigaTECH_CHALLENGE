<?php

namespace App\Form\Model;

use App\Entity\Club;

class ClubDto{
    public $id;
    public $name;
    public $budget;

    public static function createFromClub(Club $club): self{
        $dto = new self();
        $dto->name = $club->getName();
        $dto->budget = $club->getBudget();
        $dto->id = $club->getId();

        return $dto;


    }
}