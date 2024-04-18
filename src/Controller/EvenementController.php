<?php

namespace App\Controller;
use App\Entity\Participation;
use App\Entity\User;
use App\Entity\Evenement;
use App\Form\EvenementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[Route('/evenement')]
class EvenementController extends AbstractController
{
    #[Route('/new', name: 'app_evenement_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $evenement = new Evenement();
    $form = $this->createForm(EvenementType::class, $evenement);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Handle image upload if provided
        $imageFile = $form->get('image')->getData();
        if ($imageFile) {
            $newFilename = uniqid().'.'.$imageFile->guessExtension();
            // Move the uploaded file to the image directory
            $imageFile->move(
                $this->getParameter('kernel.project_dir').'/public/uploads',
                $newFilename
            );
            // Set the image path to the entity
            $evenement->setImage($newFilename);
        }

        $entityManager->persist($evenement);
        $entityManager->flush();

        return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('evenement/new.html.twig', [
        'evenement' => $evenement,
        'form' => $form,
    ]);
}
#[Route('/', name: 'app_evenement_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $evenements = $entityManager
            ->getRepository(Evenement::class)
            ->findAll();

        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenements,
        ]);
    }
    
#[Route('/events', name: 'app_evenement_events', methods: ['GET'])]
public function events(EntityManagerInterface $entityManager): Response
{
    $evenements = $entityManager
        ->getRepository(Evenement::class)
        ->findAll();

    return $this->render('evenement/event.html.twig', [
        'evenements' => $evenements,
    ]);
}


    #[Route('/{id}', name: 'app_evenement_show', methods: ['GET'])]
    public function show(Evenement $evenement): Response
    {
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_evenement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_evenement_delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($evenement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}/participate', name: 'app_evenement_participate', methods: ['POST'])]
public function participate(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
{
    // Create a new participation for the user with ID 3 and the specified event
    $participation = new Participation();
    $participation->setIdUser($entityManager->getReference(User::class, 3)); // Set user ID statically to 3
    $participation->setIdEvenement($evenement);

    // Increment the number of participants by 1 for the event
    $nbrDeParticipant = $participation->getNbrDeParticipant() + 1;
    $participation->setNbrDeParticipant($nbrDeParticipant);

    $entityManager->persist($participation);
    $entityManager->flush();

    // Redirect to the same page with filtered events
    $allEvents = $entityManager
        ->getRepository(Evenement::class)
        ->findAll();

    // Filter events to only include those the user hasn't participated in
    $filteredEvents = [];
    foreach ($allEvents as $event) {
        $participation = $entityManager
            ->getRepository(Participation::class)
            ->findOneBy(['idUser' => 3, 'idEvenement' => $event]);

        if (!$participation) {
            $filteredEvents[] = $event;
        }
    }

    return $this->render('evenement/event.html.twig', [
        'evenements' => $filteredEvents,
    ]);

}

    
}

