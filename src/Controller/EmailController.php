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
use App\Entity\EmailSender;

use App\Entity\Abonnement;




use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use League\OAuth2\Client\Provider\AbstractProvider;

class EmailController extends AbstractController
{
    #[Route('/send-email', name: 'app_send_email', methods: ['GET', 'POST'])]
    public function index(MailerInterface $mailer ): Response
    {
     $emailSender = new EmailSender();
       $emailSender->sendEmail();
       dd("helllo");
    }
}
