<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use League\OAuth2\Client\Provider\Google;

use Symfony\Bridge\Twig\Mime\TemplatedEmail as Email;
use App\Entity\Abonnement;

class EmailController extends AbstractController
{
    private $googleProvider;

    public function __construct(Google $googleProvider)
    {
        $this->googleProvider = $googleProvider;
    }

    #[Route('/send-email', name: 'app_send_email',  methods: ['POST'])]
    public function sendEmail(Request $request, MailerInterface $mailer): Response
    {
        // Configure the Google OAuth2 client
        $options = [
            'clientId' => '1050520473007-gtb12takp27cm6mrrltu9ffk8al9dv2l.apps.googleusercontent.com',
            'clientSecret' => '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',
            'redirectUri' => 'http://127.0.0.1:8000/abonnement'
        ];
        // Check if we received the authorization code from Google
        $code = $request->query->get('code');

        if ($code) {
            // Exchange the authorization code for an access token
            $accessToken = $this->googleProvider->getAccessToken('authorization_code', [
                'code' => $code
            ]);

            // Use the access token to send emails via Gmail
            $mailer->send($this->createEmail($accessToken));

            $this->addFlash('success', 'Email sent successfully.');

            return $this->redirectToRoute('app_abonnement_index');
        }

        // Redirect the user to Google for authorization
        $authorizationUrl = $this->googleProvider->getAuthorizationUrl();
        return $this->redirect($authorizationUrl);
    }

    private function createEmail(string $accessToken): Email
    {
        // Récupérer l'adresse e-mail du destinataire à partir de l'entité Abonnement
        // Récupérer l'ID de l'abonnement à partir de la requête
        $abonnementId = $request->request->get('abonnement_id');

        // Récupérer l'entité Abonnement à partir de l'ID
        $abonnement = $this->getDoctrine()->getRepository(Abonnement::class)->find($abonnementId);

        // Vérifier si l'abonnement existe
        if (!$abonnement) {
            throw $this->createNotFoundException('L\'abonnement avec l\'ID '.$abonnementId.' n\'existe pas.');
        }

        // Récupérer l'utilisateur associé à l'abonnement
        $user = $abonnement->getIdAdherent();

        // Vérifier si l'utilisateur existe
        if (!$user) {
            throw $this->createNotFoundException('L\'utilisateur associé à l\'abonnement avec l\'ID '.$abonnementId.' n\'existe pas.');
        }

        // Récupérer l'adresse e-mail de l'utilisateur
        $recipientEmail = $user->getEmail();

        // Créer et envoyer l'e-mail
        $email = (new Email())
            ->from('nadazaghdoud.5@gmail.com')
            ->to($recipientEmail)
            ->subject('Reminder: Renew Your Subscription')
            ->html('<p>Dear member, your subscription will expire soon. Please renew it.</p>');

        // Set the access token in the headers to authenticate with Gmail
        $email->getHeaders()->addTextHeader('Authorization', 'Bearer ' . $accessToken);

        return $email;
    }
}
