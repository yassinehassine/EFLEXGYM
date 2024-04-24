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


use App\Entity\Abonnement;




use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use League\OAuth2\Client\Provider\AbstractProvider;

class EmailController extends AbstractController
{
    #[Route('/send-email', name: 'app_send_email', methods: ['GET', 'POST'])]

    public function sendEmail(Request $request, AbonnementRepository $abonnementRepository, MailerInterface $mailer): Response
    {
        $abonnementId = $request->request->get('abonnement_id');

        // Query the database to find the Abonnement entity by its ID
        $abonnement = $abonnementRepository->find($abonnementId);
    
        // Check if the Abonnement entity with the provided ID exists
        if (!$abonnement) {
            // If not found, throw a NotFoundException
            throw $this->createNotFoundException('L\'abonnement avec l\'ID '.$abonnementId.' n\'existe pas.');
        }
    
        // Retrieve the user associated with the Abonnement
        $user = $abonnement->getIdAdherent();

        if (!$user) {
            throw $this->createNotFoundException('L\'utilisateur associé à l\'abonnement avec l\'ID '.$abonnementId.' n\'existe pas.');
        }

    
        // Retrieve the recipient email
        $recipientEmail = $user->getEmail();
        
        // Create an Email object
        $email = (new Email())
            ->from('nadazaghdoud.5@gmail.com')
            ->to($recipientEmail)
            ->subject('A Cool Subject!')
            ->text('The plain text version of the message.');
    
        // Create SMTP transport with OAuth2 provider information
        $provider = new Google([
            'clientId'     => '1050520473007-gtb12takp27cm6mrrltu9ffk8al9dv2l.apps.googleusercontent.com',
            'clientSecret' => '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',
            'redirectUri'  => 'http://127.0.0.1:8000/send-email', // Modify the redirect URL accordingly
        ]);
        
        // Get the authorization URL from the OAuth2 provider
        $authorizationUrl = $provider->getAuthorizationUrl();
        
        // Redirect the user to the authorization URL to start the authorization process
        return $this->redirect($authorizationUrl);
    }
}
