<?php

namespace App\Repository;

use App\Entity\Player;
use App\Entity\Club;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Player>
 *
 * @method Player|null find($id, $lockMode = null, $lockVersion = null)
 * @method Player|null findOneBy(array $criteria, array $orderBy = null)
 * @method Player[]    findAll()
 * @method Player[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Player::class);
    }

    public function getSalaryByPlayer($id_club = 0)
    {
        $response = $this->createQueryBuilder("p")
            ->select("sum(p.salary) salary")
            ->andWhere("p.id_club = :id_club")
            ->setParameter("id_club", $id_club)
            ->getQuery();

        return $response->getResult();
    }

    public function findByPlayerClub($id_club, $player_name, $pag = false)
    {
        $response = $this->createQueryBuilder("p")
            ->select("p.id, p.id_club ,p.name, p.salary, p.email")
            ->innerJoin(Club::class, "c")
            ->where("c.id = p.id_club");
        if ($id_club != null && $id_club != "") {
            $response
                ->andWhere("p.id_club = :club_id")
                ->setParameter("club_id", $id_club);
        }

        if ($player_name != null && $player_name != "") {
            $response
                ->andWhere("p.name = :club_player")
                ->setParameter("club_player", $player_name);
        }

        if ($pag) {
            $response->setFirstResult(5 * ($pag - 1))->setMaxResults($pag * 5);
        }
        return $response->getQuery()->getResult();
    }
}
