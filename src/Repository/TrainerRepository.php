<?php

namespace App\Repository;

use App\Entity\Trainer;
use App\Entity\Club;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Trainer>
 *
 * @method Trainer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trainer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trainer[]    findAll()
 * @method Trainer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrainerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trainer::class);
    }


    public function getSalaryByTrainer($id_club = 0)
    {
        $response = $this->createQueryBuilder("t")
            ->select(" sum(t.salary) salary")
            ->andWhere("t.id_club = :id_club")
            ->setParameter("id_club", $id_club)
            ->getQuery();
            
        return $response->getResult();
    }
}
