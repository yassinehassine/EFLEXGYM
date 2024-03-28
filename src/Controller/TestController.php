<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\BilanFinancier;
use Doctrine\Persistence\ObjectManager;


#[Route('/test')]
class TestController extends AbstractController
{
    #[Route('/{id}', name: 'test_repository_methods')]
    public function testRepositoryMethods($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $bilanFinancier = $entityManager->find(BilanFinancier::class, $id);
        
        // Test recupererRevenuAbonnements method
        $revenusAbonnements = $bilanFinancier->recupererRevenuAbonnements($entityManager);
        dump($revenusAbonnements);
        
        // Test recupererSalairesCoachs method
        $salairesCoachs = $bilanFinancier->recupererSalairesCoachs($entityManager);
        dump($salairesCoachs);
        
        // Test recupererRevenusProduits method
        $revenusProduits = $bilanFinancier->recupererRevenusProduits($entityManager);
        dump($revenusProduits);
        
        // Test calculerProfit method
        $profit = $bilanFinancier->calculerProfit();
        dump($profit);
        
        // You can return a response or view to see the dump results
        return new Response();
    }
}
