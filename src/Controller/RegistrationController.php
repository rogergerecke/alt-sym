<?php

namespace App\Controller;

use App\Controller\Admin\AdminDashboardController;
use App\Controller\Admin\AdminUserCrudController;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Service\AdminMessagesHandler;
use App\Service\SystemOptionsService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Exception;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    private $crudUrlGenerator;
    /**
     * @var SystemOptionsService
     */
    private $systemOptions;
    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * @var array
     */
    private $product;

    /**
     * @var string
     */
    private $product_text = '';
    /**
     * @var AdminMessagesHandler
     */
    private $adminMessagesHandler;


    /**
     * RegistrationController constructor.
     * @param SystemOptionsService $systemOptions
     * @param CrudUrlGenerator $crudUrlGenerator
     * @param Swift_Mailer $mailer
     * @param AdminMessagesHandler $adminMessagesHandler
     */
    public function __construct(
        SystemOptionsService $systemOptions,
        CrudUrlGenerator $crudUrlGenerator,
        Swift_Mailer $mailer,
        AdminMessagesHandler $adminMessagesHandler
    ) {
        $this->systemOptions = $systemOptions;
        $this->crudUrlGenerator = $crudUrlGenerator;
        $this->mailer = $mailer;
        $this->adminMessagesHandler = $adminMessagesHandler;
    }

    /**
     * @Route("/register", name="app_register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     * @throws Exception
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder
    ): Response {

        // if the user logged in redirect to backend
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->addFlash('warning', 'Sie sind angemeldet und können kein Konto anlegen.');

            return $this->redirectToRoute('user');
        }

        // get the symfony session wee need it later
        $session = $request->getSession();

        // new registration formular object
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        #########################################################################################
        # 1. From Entry Product Types Page
        # Handle the chosen product session to handle dynamic
        # formular marketing text
        #
        #########################################################################################

        if (null !== $request->request->all('register')) {
            switch (key($request->request->all('register'))) {
                case 'free_account':
                    $this->product_text = 'für null Euro';
                    $this->product = 'free_account';
                    break;
                case 'base_account':
                    $this->product_text = 'für 49,98 € / Jahr inkl. 19% MwSt.';
                    $this->product = 'base_account';
                    break;
                case 'premium_account':
                    $this->product_text = 'für 99,96 € / Jahr inkl. 19% MwSt.';
                    $this->product = 'premium_account';
                    break;
                case 'leisure_offer':
                    $this->product_text = 'für 49,98 € / Jahr inkl. 19% MwSt.';
                    $this->product = 'leisure_offer';
                    break;
                case 'banner_advertising':
                    $this->product_text = 'für ??,?? € / Jahr inkl. 19% MwSt.';
                    $this->product = 'banner_advertising';
                    break;
                default:
                    $this->addFlash('danger', 'Noch kein Packet ausgewählt.');
                    $this->product = 'free_account';
            }

            // set the chosen product to the session
            $session->set('account_type', $this->product);
            $session->set('packed_type_massage', $this->product_text);
        }

        ###########################################################################
        # 2. From Registration Page
        # Grab the submitted registration formular data
        # Build a new user account
        #
        ###########################################################################
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );


            // build one year account live time for new user
            $unix_one_year = strtotime(date("Y-m-d", mktime())." + 365 day");
            $oneYear = date('d.m.Y', $unix_one_year);
            $Year = date('Y-m-d', $unix_one_year);

            // create a new user entity
            $user->setPartnerId(rand(11111, 99999));
            $user->setUserPrivileges([$this->product]);
            $user->setStatus(false);
            $user->setRunTime(new \DateTime($Year));
            $user->setName($form->get('name')->getData());
            // fire the data
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $id = $user->getId(); // new ID

            // send a welcome message to the new user
            $receiver_email = $form->get('email')->getData();
            $email_template_vars = [
                'name'                      => $form->get('name')->getData(),
                'registration_member_email' => $form->get('email')->getData(),
                'support_email_address'     => $this->systemOptions->getSupportEmailAddress(),
                'oneYear'                   => $oneYear,
            ];
            $this->sendRegistrationMail($receiver_email, $email_template_vars);

            // inform the admin about new user
            $this->setAdminMessage($id);

            // redirect the new user to backend
            $this->addFlash('success', 'Ihr neues Konto wurde angelegt.  Sie können Sich jetzt einlogen.');

            return $this->redirectToRoute('app_login');
        }


        // we has a selection of account type?
        if ($session->has('account_type')) {
            $this->product_text = $session->get('packed_type_massage');
        }

        return $this->render(
            'registration/register.html.twig',
            [
                'registrationForm'    => $form->createView(),
                'packed_type_massage' => $this->product_text,
            ]
        );
    }

    ##################################################################
    #
    # Helper function
    #
    ##################################################################

    /**
     * Save the admin message the database
     * whit link to the new user for validation
     * @param $id
     */
    protected function setAdminMessage($id): void
    {
        $button = "<a href='".$this->createEditUrl($id)."' class='btn btn-sm btn-info'>$id prüfen</a>";
        $this->adminMessagesHandler->addWarning(
            "Das neue Konto muss geprüft werden. $button",
            "Registrierung",
            'Ein neuer Benutzer hat sich registriert'
        );
    }

    /**
     * Send the Registration mail to the new user
     * with the dynamic twig templates vars
     * @param string $receiver_email
     * @param array $email_template_vars
     */
    protected function sendRegistrationMail(string $receiver_email, array $email_template_vars): void
    {
        // registration done send welcome massage
        // do anything else you need here, like send an email
        $message = new \Swift_Message('Ihre Registrierung bei Altmühlsee');

        // send a copy to
        if (null !== ($this->systemOptions->getCopiedReviverEmailAddress())) {
            $message->setCc($this->systemOptions->getCopiedReviverEmailAddress());
        }

        // if developer mode send mails to the developer
        if (null !== ($this->systemOptions->getTestEmailAddress())) {
            $message->setTo($this->systemOptions->getTestEmailAddress());
        } else {
            // real receiver email address from the formular
            $message->setTo($receiver_email);
        }

        $message
            ->setFrom($this->systemOptions->getMailSystemAbsenceAddress())
            ->setBody(
                $this->renderView(
                // Email-Template templates/emails/registration.html.twig
                    'emails/registration.html.twig',
                    $email_template_vars
                ),
                'text/html'
            );

        $this->mailer->send($message);
    }

    /**
     * Build the User-Profil Edit Url for the admin
     * @param $id
     * @return CrudUrlBuilder
     */
    protected function createEditUrl($id): string
    {
        return $this->crudUrlGenerator->build()
            ->setDashboard(AdminDashboardController::class)
            ->setController(AdminUserCrudController::class)
            ->setAction(Action::EDIT)
            ->setEntityId($id);
    }
}
