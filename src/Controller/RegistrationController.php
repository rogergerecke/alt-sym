<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param Swift_Mailer $mailer
     * @param UserRepository $userRepository
     * @return Response
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        Swift_Mailer $mailer,
        UserRepository $userRepository
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // creat the new partner id
            /*$last_id = $userRepository->findOneBy([],['partner_id'=>'A']);
            $user->setPartnerId(rand(1111,9999));*/
            $user->setName($form->get('name'));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email
            $message = new \Swift_Message('Wilkommen bei Altmühlsee');

            // send a copy to (set in .env)
            if (isset($_ENV['CC_EMAIL'])) {
                $message->setCc($_ENV['CC_EMAIL']);
            }

            // if developer mode
            if (isset($_ENV['TEST_MAIL_ADDRESS'])) {
                $message->setTo($_ENV['TEST_MAIL_ADDRESS']);
            } else {
                $message->setTo($form->get('email'));
            }

            $message
                ->setFrom($_ENV['MAIL_SYSTEM_ABSENCE_ADDRESS'])
                ->setBody(
                    $this->renderView(
                    // Email-Template templates/emails/registration.html.twig
                        'emails/registration.html.twig',
                        [
                            'name' => $form->get('name'),
                            'registration_member_email' => $form->get('email'),
                            'support_email_address' => $_ENV['SUPPORT_EMAIL_ADDRESS'],
                        ]
                    ),
                    'text/html'
                );

            $mailer->send($message);


            return $this->redirectToRoute('member');
        }

        // open route /register is login true redirect to member aria
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('member');
        }

        return $this->render(
            'registration/register.html.twig',
            [
                'registrationForm' => $form->createView(),
            ]
        );
    }
}
