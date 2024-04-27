<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AbonnementRepository;
use League\OAuth2\Client\Provider\Google;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Transport\Dsn;
use App\service\EmailSender;

use App\Entity\Abonnement;




use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use League\OAuth2\Client\Provider\AbstractProvider;

class EmailController extends AbstractController
{
  #[Route('/send-email', name: 'app_send_email', methods: ['POST'])]
  public function sendEmail(Request $request, AbonnementRepository $abonnementRepository, EmailSender $emailSender): Response
  {
      $abonnementId = $request->request->get('abonnement_id');
      $abonnement = $abonnementRepository->find($abonnementId);

      if (!$abonnement) {
          throw $this->createNotFoundException('Abonnement non trouvé.');
      }

      $adherent = $abonnement->getIdAdherent();
      
      if (!$adherent) {
          throw $this->createNotFoundException('Adhérent non trouvé pour cet abonnement.');
      }

      $adherentEmail = $adherent->getEmail();

      if (!$adherentEmail) {
          throw $this->createNotFoundException('Adresse e-mail non trouvée pour cet adhérent.');
      }

      // Envoyer un e-mail à l'adresse de l'adhérent
      $emailSender->sendEmail($adherentEmail);

      // Redirection vers la liste des abonnements après envoi de l'e-mail
      return $this->redirectToRoute('app_abonnement_index');
  }
}