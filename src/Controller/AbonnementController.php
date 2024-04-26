<?php

namespace App\Controller;

use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Abonnement;
use App\Form\AbonnementType;
use App\Repository\AbonnementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/abonnement')]
class AbonnementController extends AbstractController
{
    #[Route('/', name: 'app_abonnement_index', methods: ['GET'])]
    public function index(Request $request, AbonnementRepository $abonnementRepository ,  PaginatorInterface $paginator): Response
    {

        $abonnements= $abonnementRepository->findAll();
        $typeFilter = $request->query->get('type_filter');
        $nomAdherent = $request->query->get('nom_adherent');
      
    
        if ($nomAdherent) {
            $abonnements = $abonnementRepository->findByNomAdherent($nomAdherent);
        } else {
            $abonnements = $typeFilter ? $abonnementRepository->findBy(['type' => $typeFilter]) : $abonnementRepository->findAll();
        }

        $abonnements = $paginator->paginate(
            $abonnements, 
            $request->query->getInt('page', 1),
            10
        );
   
    
        return $this->render('abonnement/index.html.twig', [
            'abonnements' => $abonnements,
        ]);
    }
    

    #[Route('/new', name: 'app_abonnement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $abonnement = new Abonnement();
    $form = $this->createForm(AbonnementType::class, $abonnement);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

        $entityManager->persist($abonnement);
        $entityManager->flush();

        return $this->redirectToRoute('app_abonnement_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('abonnement/new.html.twig', [
        'abonnement' => $abonnement,
        'form' => $form->createView(),
    ]);
}


    #[Route('/{id}', name: 'app_abonnement_show', methods: ['GET'])]
    public function show(Abonnement $abonnement): Response
    {
        return $this->render('abonnement/show.html.twig', [
            'abonnement' => $abonnement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_abonnement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Abonnement $abonnement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AbonnementType::class, $abonnement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_abonnement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('abonnement/edit.html.twig', [
            'abonnement' => $abonnement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_abonnement_delete', methods: ['POST'])]
    public function delete(Request $request, Abonnement $abonnement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$abonnement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($abonnement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_abonnement_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/monA/{user_id}', name: 'app_abonnement_monA', methods: ['GET'])]
    public function monA(int $user_id, AbonnementRepository $abonnementRepository): Response
    {
       
        $abonnement = $abonnementRepository->findOneBy(['id_adherent' => $user_id]);
    
        if (!$abonnement) {
            throw $this->createNotFoundException('Aucun abonnement trouvé pour cet utilisateur.');
        }
    
    
        return $this->render('abonnement/monAbonnement.html.twig', [
            'abonnement' => $abonnement,
        ]);
    }

}