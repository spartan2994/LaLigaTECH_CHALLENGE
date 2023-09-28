<?php

namespace App\Repository;

use App\Entity\Club;
use App\Entity\Player;
use App\Entity\Trainer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Club>
 *
 * @method Club|null find($id, $lockMode = null, $lockVersion = null)
 * @method Club|null findOneBy(array $criteria, array $orderBy = null)
 * @method Club[]    findAll()
 * @method Club[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClubRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Club::class);
    }

    public function findByClub($club_name)
    {
       

        $club = $this->createQueryBuilder('c')
                ->andWhere('c.name = :club_name')
                ->setParameter('club_name', $club_name)
                ->getQuery()
                ->getResult();

          

        return $club;
    }



    public function getBudgetByClub($salary, $id_club)
    {
        if ($id_club) {
            $salary_players = $this->createQueryBuilder("c")
                ->select("sum(p.salary) salary")
                ->innerJoin(Player::class, "p")
                ->where("c.id = p.id_club")
                ->andWhere("c.id = :id_club")
                ->setParameter("id_club", $id_club)
                ->getQuery()
                ->getResult()[0]["salary"];

            $salary_trainers = $this->createQueryBuilder("c")
                ->select("sum(t.salary) salary")
                ->innerJoin(Trainer::class, "t")
                ->where("c.id = t.id_club")
                ->andWhere("c.id = :id_club")
                ->setParameter("id_club", $id_club)
                ->getQuery()
                ->getResult()[0]["salary"];

            $budget_club = $this->createQueryBuilder("c")
                ->select("c.budget")
                ->andWhere("c.id = :id_club")
                ->setParameter("id_club", $id_club)
                ->getQuery()
                ->getResult()[0]["budget"];

            $budget = $budget_club - ($salary_trainers + $salary_players);

           
                return $budget;
            
        } elseif (!$id_club) {
            return 0;
        }

        return 0;
    }
}
