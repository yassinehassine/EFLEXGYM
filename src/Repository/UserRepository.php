<?php
namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\BilanFinancier;


class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
    public function calculateSalairesCoachs(BilanFinancier $bilanFinancier): ?float
{
    $entityManager = $this->getEntityManager();
    $query = $entityManager->createQuery(
        'SELECT SUM(u.salaire) AS salaires_coachs
        FROM App\Entity\User u
        WHERE u.role = :coach AND u.idBilanFinancier = :id'
    )->setParameters(['coach' => 'COACH', 'id' => $bilanFinancier->getId()]);

    $result = $query->getSingleScalarResult();

    return $result ? (float) $result : null;
}

    

   
}