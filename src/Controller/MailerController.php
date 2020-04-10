<?php
// src/Controller/MailerController.php
namespace App\Controller;

use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MailerController extends AbstractController
{
    /**
     * @Route("/email")
     * @param Swift_Mailer $mailer
     * @return Response
     */
    public function sendEmail(Swift_Mailer $mailer)
    {

        $message = (new \Swift_Message('Hello Email'))
            ->setFrom($_ENV['MAIL_SYSTEM_ABSENCE_ADDRESS'])
            ->setTo('roger.gerecke@gmail.com')
            ->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    'emails/registration.html.twig',
                    [
                        'name'        => 'Max Mustermann',
                        'registration_member_email'=>'test',
                        'support_email_address' => $_ENV['SUPPORT_EMAIL_ADDRESS'],
                    ]
                ),
                'text/html'
            );

        $mailer->send($message);

        return $this->render(
            'empty.html.twig',
            [
                'registrationForm' => '$form->createView()',
            ]
        );
    }

}