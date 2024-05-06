<?php

namespace App\Controller;

use App\Service\panierService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Security;

class PanierController extends AbstractController
{
    private $passwordHasher;
    private $security;
    private $entityManager;

    public function __construct(UserPasswordHasherInterface $passwordHasher, Security $security, EntityManagerInterface $entityManager)
    {
        $this->passwordHasher = $passwordHasher;
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    #[Route('/panier', name: 'app_panier')]
    public function index(panierService $panierService, UserRepository $userRep, Request $request): Response
    {
        // Get the currently logged-in user
        $user = $this->getUser();

        // Check if a user is logged in
        if ($user) {
            $id_user = $user->getId();

            // Fetch panier items for the dynamic user
            $panierData = $panierService->getCartItems($id_user);
            $panierItemsCount = count($panierData);

            // Calculate total price of items in the panier
            $totalPrice = array_reduce($panierData, function ($total, $product) {
                return $total + $product->getIdProduit()->getPrix();
            }, 0);

            // Render the panier.html.twig template with necessary data
            return $this->render('panier/panier.html.twig', [
                'controller_name' => 'PanierController',
                'panierData' => $panierData,
                'totalPrice' => $totalPrice,
                'panierItemsCount' => $panierItemsCount,
            ]);
        } else {
            // Handle the case where no user is logged in
            // For example, redirect to app_login page or display an error message
            return $this->redirectToRoute('app_login'); // Redirect to app_login page
        }
    }

    #[Route('/addToPanier/{idp}', name: 'app_addToPanier')]
    public function addToPanier(Request $request, $idp, panierService $panierService, UserRepository $userRep, ProduitRepository $pr): Response
    {
        // Get the currently logged-in user
        $user = $this->getUser();

        // Check if a user is logged in
        if ($user) {
            $id_user = $user->getId();

            // Add the item to the cart for the dynamic user
            $panierService->addToCart($id_user, $idp, $userRep, $pr);

            // Add flash message
            $this->addFlash('command_ajoute', 'Article ajouté au panier');

            return $this->redirectToRoute('produits');
        } else {
            // Handle the case where no user is logged in
            // For example, redirect to app_login page or display an error message
            return $this->redirectToRoute('app_login'); // Redirect to app_login page
        }
    }

    #[Route('/removeFromPanier/{idp}', name: 'app_removeFromPanier')]
    public function removeFromPanier($idp, panierService $panierService, PanierRepository $panierRep): Response
    {
        // Get the currently logged-in user
        $user = $this->getUser();

        // Check if a user is logged in
        if ($user) {
            $id_user = $user->getId();

            // Remove the selected article from the user's panier
            $panierService->removeFromCart($idp, $panierRep);

            // Redirect back to the panier page
            return $this->redirectToRoute('app_panier');
        } else {
            // Handle the case where no user is logged in
            // For example, redirect to app_login page or display an error message
            return $this->redirectToRoute('app_login'); // Redirect to app_login page
        }
    }
}