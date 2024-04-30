<?php

namespace App\Controller;


use App\Entity\Reservation;
use App\Entity\Planning;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security; // Import Symfony's Security component
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\HttpFoundation\Session\SessionInterface;



#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager ,FlashBagInterface $flashBag , FlashyNotifier $flashy, SessionInterface $session): Response
{
    // Get the planning ID from the URL parameter
    $planningId = $request->query->get('planningId');
    // Fetch the planning entity based on the ID
    $planning = $entityManager->getRepository(Planning::class)->find($planningId);
    /// Count the number of reservations for the planning
    $reservationCount = $entityManager->getRepository(Reservation::class)->count(['idPlaning' => $planningId]);

    // Check if the number of reservations exceeds the maximum capacity
    if ($reservationCount >= $planning->getNbPlaceMax()) {
        $flashBag->add('danger', 'Le planning est complet. Vous ne pouvez pas effectuer une réservation.');
        return $this->redirectToRoute('app_calendarplanning');
    }


    // Create a new reservation entity
    $reservation = new Reservation();
    $reservation->setIdPlaning($planningId); // Set the planning ID for the reservation

    // Create the reservation form
    $form = $this->createForm(ReservationType::class, $reservation);
    $form->handleRequest($request);

    // Handle form submission
    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($reservation);
        $entityManager->flush();
        // Redirect to the reservation index page after successful submission
        return $this->redirectToRoute('app_calendarplanning');
        $flashy->success('reservation avec sucss', 'http://your-awesome-link.com', [
            'time' => 10000 // Set duration to 5000 milliseconds (5 seconds)
        ]);
    }

    // Render the reservation form
    return $this->renderForm('reservation/new.html.twig', [
        'reservation' => $reservation,
        'form' => $form,
    ]);
}


    #[Route('/{idReservation}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getIdReservation(), $request->request->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
}