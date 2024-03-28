<?php

namespace App\Repository;

use App\Entity\Produit;
use App\Entity\BilanFinancier;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    public function calculateRevenusProduits(BilanFinancier $bilanFinancier): ?float
{
    $entityManager = $this->getEntityManager();
    $query = $entityManager->createQuery(
        'SELECT SUM(p.quantite * p.prix) AS revenus_produits
        FROM App\Entity\Produit p
        WHERE p.idBilanFinancier = :id'
    )->setParameter('id', $bilanFinancier->getId());

    $result = $query->getSingleScalarResult();

    return $result ? (float) $result : null;
}

}
