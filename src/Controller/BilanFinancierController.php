<?php

namespace App\Controller;


use Psr\Log\LoggerInterface;
use App\Entity\BilanFinancier;
use App\Form\BilanFinancierType;
use App\Repository\BilanFinancierRepository;
use App\Repository\AbonnementRepository;
use App\Repository\UserRepository;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/bilan/financier')]
class BilanFinancierController extends AbstractController
{
    private $entityManager;
    private $logger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        

       
    }

    #[Route('/', name: 'app_bilan_financier_index', methods: ['GET'])]
    public function index(Request $request, BilanFinancierRepository $bilanFinancierRepository): Response
    {
        $sortField = $request->query->get('sort_field', 'dateDebut'); 
        $sortDirection = $request->query->get('sort_direction', 'ASC'); 

        $validSortFields = ['dateDebut', 'revenusAbonnements', 'revenusProduits', 'profit'];
        if (!in_array($sortField, $validSortFields)) {
            throw new \InvalidArgumentException('Invalid sort field.');
        }

        $bilan_financiers = $bilanFinancierRepository->findBy([], [$sortField => $sortDirection]);

        return $this->render('bilan_financier/index.html.twig', [
            'bilan_financiers' => $bilan_financiers,
        ]);
    }
    
    
    #[Route('/{id}', name: 'app_bilan_financier_show', methods: ['GET'])]
    public function show(BilanFinancier $bilanFinancier): Response
    {
        return $this->render('bilan_financier/show.html.twig', [
            'bilan_financier' => $bilanFinancier,
 
        ]);
    }

   
    #[Route('/new', name: 'app_bilan_financier_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $bilanFinancier = new BilanFinancier();
        $form = $this->createForm(BilanFinancierType::class, $bilanFinancier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($bilanFinancier);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_bilan_financier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bilan_financier/new.html.twig', [
            'bilan_financier' => $bilanFinancier,
            'form' => $form,
        ]);
    }

    

    #[Route('/{id}/edit', name: 'app_bilan_financier_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, BilanFinancier $bilanFinancier): Response
{
    $form = $this->createForm(BilanFinancierType::class, $bilanFinancier);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      
        $this->entityManager->flush();

        return $this->redirectToRoute('app_bilan_financier_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('bilan_financier/edit.html.twig', [
        'bilan_financier' => $bilanFinancier,
        'form' => $form,
    ]);
}


#[Route('/{id}', name: 'app_bilan_financier_delete', methods: ['POST'])]
public function delete(Request $request, BilanFinancier $bilanFinancier): Response
{
    if ($this->isCsrfTokenValid('delete'.$bilanFinancier->getId(), $request->request->get('_token'))) {
        $this->entityManager->remove($bilanFinancier);
        $this->entityManager->flush();
    }

    return $this->redirectToRoute('app_bilan_financier_index', [], Response::HTTP_SEE_OTHER);
}

    #[Route('/get-revenus-abonnements/{id}', name: 'app_bilan_financier_get_revenus_abonnements', methods: ['GET'])]
    public function getRevenusAbonnements(int $id, AbonnementRepository $abonnementRepository): JsonResponse
    {
        $bilanFinancier = $this->getDoctrine()->getRepository(BilanFinancier::class)->find($id);
        $revenusAbonnements = $abonnementRepository->calculateRevenusAbonnements($bilanFinancier);
        
        return $this->json(['revenusAbonnements' => $revenusAbonnements]);
    }
    

    #[Route('/get-revenus-produits/{id}', name: 'app_bilan_financier_get_revenus_produits', methods: ['GET'])]
public function getRevenusProduits(int $id, ProduitRepository $produitRepository): JsonResponse
{
    $bilanFinancier = $this->getDoctrine()->getRepository(BilanFinancier::class)->find($id);
    $revenusProduits = $produitRepository->calculateRevenusProduits($bilanFinancier);
    
    return $this->json(['revenusProduits' => $revenusProduits]);
}

#[Route('/get-salaires-coachs/{id}', name: 'app_bilan_financier_get_salaires_coachs', methods: ['GET'])]
public function getSalairesCoachs(int $id, UserRepository $userRepository): JsonResponse
{
    $bilanFinancier = $this->getDoctrine()->getRepository(BilanFinancier::class)->find($id);
    $salairesCoachs = $userRepository->calculateSalairesCoachs($bilanFinancier);
    
    return $this->json(['salairesCoachs' => $salairesCoachs]);
}



#[Route('/calculer-profit/{id}', name: 'app_bilan_financier_calculer_profit', methods: ['GET'])]
public function calculerProfit(int $id, BilanFinancierRepository $bilanFinancierRepository, AbonnementRepository $abonnementRepository, ProduitRepository $produitRepository, UserRepository $userRepository): JsonResponse
{
    $bilanFinancier = $bilanFinancierRepository->find($id);
    
    $revenusAbonnements = $abonnementRepository->calculateRevenusAbonnements($bilanFinancier);
    $revenusProduits = $produitRepository->calculateRevenusProduits($bilanFinancier);
    $salairesCoachs = $userRepository->calculateSalairesCoachs($bilanFinancier);
    

    $bilanFinancier->setRevenusAbonnements($revenusAbonnements);
    $bilanFinancier->setRevenusProduits($revenusProduits);
    $bilanFinancier->setSalairesCoachs($salairesCoachs);
  
    $profit = $revenusAbonnements + $revenusProduits - $salairesCoachs - ($bilanFinancier->getDepenses() ?? 0) - ($bilanFinancier->getPrixLocation() ?? 0);
    $bilanFinancier->setProfit($profit);
    
    
    $this->entityManager->flush();

    return $this->json(['profit' => $profit]);
}

#[Route('/statistique', name: 'stat', methods: ['GET', 'POST'])]
public function statistiques(BilanFinancierRepository $bilanFinancierRepository, AbonnementRepository $abonnementRepository, ProduitRepository $produitRepository, UserRepository $userRepository)
{
    // Récupérer les données pour calculer les statistiques des profits et des revenus d'abonnements
    $bilanFinanciers = $bilanFinancierRepository->findAll(); // Récupérer tous les bilans financiers
    
    // Initialiser les tableaux pour stocker les données
    $dates = [];
    $profits = [];
    $revenuesAbonnements = [];
    $revenuesProduits = [];
    
    // Calculer les statistiques des profits et des revenus d'abonnements pour chaque bilan financier
    foreach ($bilanFinanciers as $bilanFinancier) {
        $dates[] = $bilanFinancier->getDateDebut()->format('Y-m-d'); // Stocker la date de début du bilan financier
        $profits[] = $bilanFinancier->getProfit(); // Stocker le profit du bilan financier
        // Calculer les revenus d'abonnements pour ce bilan financier en utilisant la méthode appropriée de votre repository
        $revenuesAbonnements[] = $abonnementRepository->calculateRevenusAbonnements($bilanFinancier);
        $revenuesProduits[] = $produitRepository->calculateRevenusProduits($bilanFinancier);
    }
    
    // Récupérer le nombre d'adhérents et de coachs dans la base de données
    $nbAdherents = $userRepository->countUsersByRole('adherent');
    $nbCoachs = $userRepository->countUsersByRole('coach');
    
    // Rendre la vue avec les données des statistiques des profits et des revenus d'abonnements, ainsi que le nombre d'adhérents et de coachs
    return $this->render('bilan_financier/stats.html.twig', [
        'dates' => json_encode($dates),
        'profits' => json_encode($profits),
        'revenuesAbonnements' => json_encode($revenuesAbonnements),
        'revenuesProduits' => json_encode($revenuesProduits),
        'nbAdherents' => $nbAdherents,
        'nbCoachs' => $nbCoachs,
    ]);
}

}
