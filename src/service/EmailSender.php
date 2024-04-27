<?php
 
namespace App\service;
 
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Transport\Dsn;
 
class EmailSender
{
    public function sendEmail(string $recipientEmail):void
    {
        // Create a Transport object
        $transport = Transport::fromDsn('smtp://nadazaghdoud.5@gmail.com:kczd%20vyvt%20glul%20hzkg@smtp.gmail.com:587');
 
        // Create a Mailer object
        $mailer = new Mailer($transport);
 
        // Create an Email object
        $email = (new Email())
            ->from('nadazaghdoud.5@gmail.com')
            ->to($recipientEmail)
            ->subject('A Cool Subject!')
            ->text('The plain text version of the message.');
 
        // Send the email
        $mailer->send($email);
    }
}
 