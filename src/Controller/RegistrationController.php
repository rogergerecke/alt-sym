<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
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

        // get the symfony session wee need it later
        $session = $request->getSession();

        // build the registration form
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        // create additional text to register form for more personality
        $packed_type_selection = false;
        $packed_type_massage = false;
        $packed_type_selection = $request->get('register');//entry came from entry page


        if (null !== $packed_type_selection) {

            switch (key($packed_type_selection)) {
                case 'free_account':
                    $packed_type_massage = 'für null Euro';
                    $packed_type_selection = 'free_account';
                    break;
                case 'base_account':
                    $packed_type_massage = 'für 49,98 € / Jahr inkl. 19% MwSt.';
                    $packed_type_selection = 'base_account';
                    break;
                case 'premium_account':
                    $packed_type_massage = 'für 99,96 € / Jahr inkl. 19% MwSt.';
                    $packed_type_selection = 'premium_account';
                    break;
                case 'leisure_offer':
                    $packed_type_massage = 'für 49,98 € / Jahr inkl. 19% MwSt.';
                    $packed_type_selection = 'leisure_offer';
                    break;
                case 'banner_advertising':
                    $packed_type_massage = 'für ??,?? € / Jahr inkl. 19% MwSt.';
                    $packed_type_selection = 'banner_advertising';
                    break;
                default:
                    $this->addFlash('danger', 'Ausgewähltes Packet unbekannt bitte Kontaktieren Sie den Support');
                    $packed_type_selection = 'free_account';
            }

            // add to session
            $session->set('account_type', $packed_type_selection);
            $session->set('packed_type_massage', $packed_type_massage);
        }


        //handle the submit from the registration form
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // creat the new partner id
            $user->setPartnerId(rand(11111, 99999));

            // set new user privileges for one year
            $oneYear = date('Y-m-d', strtotime(date("Y-m-d", mktime())." + 365 day"));
            $user->setUserPrivileges([$packed_type_selection => $oneYear]);


            $user->setName($form->get('name')->getData());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // registration done send welcome massage
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
                $message->setTo($form->get('email')->getData());
            }

            $message
                ->setFrom($_ENV['MAIL_SYSTEM_ABSENCE_ADDRESS'])
                ->setBody(
                    $this->renderView(
                    // Email-Template templates/emails/registration.html.twig
                        'emails/registration.html.twig',
                        [
                            'name'                      => $form->get('name')->getData(),
                            'registration_member_email' => $form->get('email')->getData(),
                            'support_email_address'     => $_ENV['SUPPORT_EMAIL_ADDRESS'],
                        ]
                    ),
                    'text/html'
                );

            $mailer->send($message);


            // after registration send the new user to the backend
            return $this->redirectToRoute('app_login');
        }

        // open route /register is login true redirect to member aria
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_login');
        }

        // we has a selection of account type?
        if ($session->has('account_type')) {
            $packed_type_massage = $session->get('packed_type_massage');
        }

        return $this->render(
            'registration/register.html.twig',
            [
                'registrationForm'    => $form->createView(),
                'packed_type_massage' => $packed_type_massage,
            ]
        );
    }
}
