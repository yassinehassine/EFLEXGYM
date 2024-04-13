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

    private $mois = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        
        // Define the array of months
       
    }

    #[Route('/', name: 'app_bilan_financier_index', methods: ['GET'])]
    public function index(Request $request, BilanFinancierRepository $bilanFinancierRepository): Response
    {
        $sortField = $request->query->get('sort_field', 'dateDebut'); // Default sort field
        $sortDirection = $request->query->get('sort_direction', 'ASC'); // Default sort direction

        // Validate the sort field
        $validSortFields = ['dateDebut', 'revenusAbonnements', 'revenusProduits', 'profit'];
        if (!in_array($sortField, $validSortFields)) {
            throw new \InvalidArgumentException('Invalid sort field.');
        }

        // Get all financial statements sorted according to the sort parameters
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
            'mois' => $this->mois, // Use the months array here
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
    
    // Récupérer les nouvelles valeurs des abonnements, des produits et des salaires des coachs
    $revenusAbonnements = $abonnementRepository->calculateRevenusAbonnements($bilanFinancier);
    $revenusProduits = $produitRepository->calculateRevenusProduits($bilanFinancier);
    $salairesCoachs = $userRepository->calculateSalairesCoachs($bilanFinancier);
    
    // Mettre à jour toutes les valeurs du bilan financier
    $bilanFinancier->setRevenusAbonnements($revenusAbonnements);
    $bilanFinancier->setRevenusProduits($revenusProduits);
    $bilanFinancier->setSalairesCoachs($salairesCoachs);
    // Vous pouvez récupérer les autres valeurs (dépenses, prix de location) de la même manière et les mettre à jour ici
    
    // Calculer le profit en fonction des nouvelles valeurs
    $profit = $revenusAbonnements + $revenusProduits - $salairesCoachs - ($bilanFinancier->getDepenses() ?? 0) - ($bilanFinancier->getPrixLocation() ?? 0);
    $bilanFinancier->setProfit($profit);
    
    // Persistez les modifications dans la base de données
    $this->entityManager->flush();

    return $this->json(['profit' => $profit]);
}
}
