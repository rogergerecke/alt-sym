<?php


namespace App\Controller\Admin;

use App\Entity\Events;
use App\Entity\Hostel;
use App\Entity\HostelGallery;
use App\Entity\RoomTypes;
use App\Entity\User;
use App\Repository\HostelRepository;
use App\Repository\StatisticsRepository;
use App\Repository\UserPrivilegesTypesRepository;
use App\Repository\UserRepository;
use App\Service\AdminMessagesHandler;
use App\Service\SystemOptionsService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swift_Mailer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserDashboardController this class contain the
 * ground configuration for the user aria include right
 * handling and main menu build
 * @IsGranted("ROLE_USER")
 * @package App\Controller\Admin
 */
class UserDashboardController extends AbstractDashboardController
{


    /**
     * @var Security
     */
    private $security;

    /**
     * @var UserInterface|null
     */
    private $user_id;
    /**
     * @var HostelRepository
     */
    private $hostelRepository;


    /**
     * Boolean value of the
     * logged in user have hostels
     *
     * @var bool
     */
    private $userHaveHostel = false;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * Contain the full user data
     * of the logged in user
     *
     * @var User|null
     */
    private $user_account;

    /**
     * Contain a array with the user privileges
     *
     * @var array|null
     */
    private $user_privileges;
    /**
     * @var int|null
     */
    private $user_hostel_ids;
    /**
     * @var array
     */
    private $user_hostels;
    /**
     * @var StatisticsRepository
     */
    private $statisticsRepository;
    /**
     * @var array
     */
    private $statistics;
    /**
     * @var CrudUrlBuilder
     */
    private $crudUrlBuilder;
    /**
     * @var CrudUrlGenerator
     */
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
     * UserDashboardController constructor.
     * @param Security $security
     * @param UserRepository $userRepository
     * @param HostelRepository $hostelRepository
     * @param StatisticsRepository $statisticsRepository
     * @param CrudUrlGenerator $crudUrlGenerator
     * @param SystemOptionsService $systemOptions
     * @param Swift_Mailer $mailer
     */
    public function __construct(
        Security $security,
        UserRepository $userRepository,
        HostelRepository $hostelRepository,
        StatisticsRepository $statisticsRepository,
        CrudUrlGenerator $crudUrlGenerator,
        SystemOptionsService $systemOptions,
        Swift_Mailer $mailer
    ) {
        // inti vars
        $this->security = $security;
        $this->userRepository = $userRepository;
        $this->hostelRepository = $hostelRepository;
        $this->statisticsRepository = $statisticsRepository;
        $this->crudUrlGenerator = $crudUrlGenerator;
        $this->systemOptions = $systemOptions;
        $this->mailer = $mailer;

        // if logged in get the logged in user id and account data
        if (null !== $this->security->getUser()) {
            $this->user_id = $this->security->getUser()->getId();
            $this->user_account = $this->userRepository->find($this->user_id);
            $this->user_privileges = $this->user_account->getUserPrivileges();
        }

        // check if user have hostel than get all ids for menu building
        if ($hostels = $this->hostelRepository->findBy(['user_id' => $this->user_id])) {
            $this->userHaveHostel = true;
            $this->user_hostels = $hostels;
        }
    }

    /**
     * @Route("/user", name="user")
     */
    public function index(): Response
    {
        // bug in EasyAdmin right handling dosn't works correct
        if ($this->isGranted('ROLE_ADMIN')) {
            $this->addFlash(
                'danger',
                'Sie sind noch als Admin angemeldet Loggen Sie sich aus um sich als normaler Benutzer anzumelden'
            );

            return $this->redirectToRoute('admin');
        }

        // get all statistics for the owned hostels for the dashboard
        $statistics = null;
        $hostel_listing_views = null;
        $hostel_detail_views = null;
        $hostel_notice = null;
        if ($this->userHaveHostel) {
            $statistics = $this->statisticsRepository->findAll();

            // create $global_page_view
            foreach ($statistics as $value) {
                $hostel_listing_views = $value->getGlobalPageView() + $hostel_listing_views;
            }

            // create detail page view and notice
            foreach ($this->user_hostels as $hostel) {
                foreach ($statistics as $statistic) {
                    if ($hostel->getId() == $statistic->getHostelId()) {
                        $hostel_detail_views[] = $statistic->getPageView();
                        $hostel_notice = $statistic->getNoticeHostel() + $hostel_notice;
                    }
                }
            }
        }

        return $this->render(
            'bundles/EasyAdmin/user_dashboard.html.twig',
            [
                'has_content_subtitle' => false,
                'statistics'           => $statistics,
                'hostel_listing_views' => $hostel_listing_views,
                'hostel_detail_views'  => $hostel_detail_views,
                'hostel_notice'        => $hostel_notice,
                'user_hostels'         => $this->user_hostels,
            ]
        );
    }

    /**
     * The account upgrade message function build the
     * upgrade link and inform the admin with the right type.
     * @Route("/user/upgrade/{product}", name="user_upgrade")
     * @param AdminMessagesHandler $adminMessagesHandler
     * @param UserPrivilegesTypesRepository $privilegesTypesRepository
     * @param SystemOptionsService $options
     * @param string $product
     * @return Response
     */
    public function upgrade(
        AdminMessagesHandler $adminMessagesHandler,
        UserPrivilegesTypesRepository $privilegesTypesRepository,
        SystemOptionsService $options,
        $product = ''
    ) {

        // if upgrade request submit handle it and inform the admin about it
        if ($product) {
            // set the status from the user to wants upgrade
            $user = $this->user_account;
            $user->setIsHeWantsUpgrade(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // inform admin about upgrade wish
            $user_privileges_types = $privilegesTypesRepository->findOneBy(['code' => $product]);
            $user_account_edit_url = $this->createEditUrl($this->user_id);
            $user_name = $this->user_account->getName();

            $this->addFlash(
                'success',
                'Ihre Upgrade anfrage wurde gesendet versendet.  
                Frau Albrecht meldet sich innerhalb von 24 Stunden bei Ihnen.'
            );

            $adminMessagesHandler->addInfo(
                "Der Benutzer möchte ein Upgrade auf: ".$user_privileges_types->getName(),
                "Kundenkonto Upgrade Wunsch",
                "Kundenkonto von <a href='".$user_account_edit_url."'>$user_name</a>"
            );

            // send admin a mail with information about

            $email_template_vars = [
                'web_site_name'          => $this->systemOptions->getWebSiteName(),
                'user_account_edit_url'  => $user_account_edit_url,
                'user_number'            => $this->user_account->getPartnerId(),
                'user_name'              => $this->user_account->getName(),
                'user_email'             => $this->user_account->getUsername(),
                'user_registration_date' => $this->user_account->getCreateAt()->format('d.m.Y'),
                'upgrade_to_product'     => $user_privileges_types->getName(),

            ];
            $this->sendUpgradeWishMail($email_template_vars);
        }

        return $this->render(
            'bundles/EasyAdmin/user_upgrade.html.twig',
            [
                'product'               => $product,
                'user_hostels'          => $this->user_hostels,
                'support_email_address' => $options->getSupportEmailAddress(),
                'support_phone_number'  => $options->getSupportPhoneNumber(),
            ]
        );
    }

    /**
     * @return Dashboard
     */
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Benutzer Bereich');
    }

    /**
     * Configure the crud global for all
     * loaded crud from here user-permission ROLE_USER
     *
     * @return Crud
     */
    public function configureCrud(): Crud
    {
        return Crud::new()
            ->setDateFormat('dd.MM.yyyy')
            ->setEntityPermission('ROLE_USER');
    }

    ######################################
    #
    #
    # Helper function
    #
    #
    #######################################

    public function configureMenuItems(): iterable
    {

        // its not premium user show upgrade message
        if (!in_array('premium_account', $this->user_privileges)) {
            yield MenuItem::linktoRoute('Upgrade to Premium', 'fa fa-star', 'user_upgrade')
                ->setCssClass('bg-success text-white pl-2');
        }

        yield MenuItem::linktoDashboard('Startseite', 'fa fa-home');

        /* HOSTEL MENU > only show with the right user privileges */
        $check = ['free_account', 'base_account', 'premium_account',];
        if ($this->isUserHavePrivileges($check)) {
            // Create the Hostel:Menu
            if ($this->userHaveHostel) {
                yield MenuItem::section('Meine Unterkunft');
                // add the hostels to menu
                foreach ($this->user_hostels as $userHostel) {
                    $hostel_name = substr($userHostel->getHostelName(), 0, 15).'...';

                    yield MenuItem::linkToCrud($hostel_name, 'fas fa-hotel', Hostel::class)
                        ->setAction('edit')
                        ->setEntityId($userHostel->getId());
                }
            }

            if (!$this->userHaveHostel) {
                yield MenuItem::linkToCrud('Unterkunft Erstellen', 'fa fa-hotel', Hostel::class)->setAction('new');
            }

            // have the user hostel so he cant add rooms and images for the hostel
            // todo filter by user
            if ($this->userHaveHostel) {
                yield MenuItem::linkToCrud('Zimmer', 'fa fa-hotel', RoomTypes::class);
                yield MenuItem::linkToCrud('Bilder', 'fa fa-image', HostelGallery::class);
            }
        }


        /* Media section */
        /*  yield MenuItem::section('Media-Einstellung', 'fa fa-image');
          yield MenuItem::linktoRoute('Bilder Hochladen', 'fa fa-image', 'elfinder')
              ->setLinkTarget('_blank')
              ->setQueryParameter('instance', 'user');*/

        /* Marketing section */
        yield MenuItem::section('Marketing-Einstellung');
        yield MenuItem::linkToCrud('Veranstaltung', 'fa fa-glass-cheers', Events::class);

        /* The Leisure Menu Offer Point */
        if ($this->isUserHavePrivileges(['leisure_offer'])) {
            yield MenuItem::linkToCrud('Freizeitangebot', 'fa fa-glass-cheers', Events::class);
        }

        /* Information section */
        yield MenuItem::section('Hilfe & Information');
        yield MenuItem::linktoRoute('Werbung', 'fa fa-question', 'static_site_contact');
        yield MenuItem::linkToUrl('Anleitung Bild bearbeiten ', 'fa fa-question', '/');
        yield MenuItem::linktoRoute('Preise', 'fa fa-question', 'static_site_entry');
        yield MenuItem::linktoRoute('Impressum', 'fa fa-question', 'static_site_imprint');
        yield MenuItem::linktoRoute('Datenschutz', 'fa fa-question', 'static_site_privacy');
        yield MenuItem::linktoRoute('Kontakt', 'fa fa-question', 'static_site_contact');
    }

    ##########################################################
    #
    #
    #   Protected Helper Function
    #
    #
    ##########################################################

    /**
     * Create the ordinary user menu top
     * right corner
     *
     * @param UserInterface $user
     * @return UserMenu
     */
    public function configureUserMenu(UserInterface $user): UserMenu
    {
        // menu build print_r($user);
        return UserMenu::new()
            // use the given $user object to get the user name
            ->setName($user->getName())
            // use this method if you don't want to display the name of the user
            ->displayUserName(true)

            // you can return an URL with the avatar image
            /* ->setAvatarUrl('https://...')*/
            /* ->setAvatarUrl($user->getProfileImageUrl())*/
            // use this method if you don't want to display the user image
            ->displayUserAvatar(true)
            // you can also pass an email address to use gravatar's service
            ->setGravatarEmail($user->getEmail())

            // you can use any type of menu item, except submenus
            ->addMenuItems(
                [
                    MenuItem::linkToCrud('Mein Konto', 'fa fa-id-card', User::class)
                        ->setAction('detail')
                        ->setEntityId($this->user_id),
                    MenuItem::section('--------------------'),
                    MenuItem::linkToLogout('Logout', 'fa fa-sign-out'),
                ]
            );
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

    /**
     * Check the user have the privileges
     * for menu options
     *
     * @param array $privileges
     * @return bool
     */
    protected function isUserHavePrivileges(array $privileges): bool
    {
        foreach ($privileges as $privilege) {
            if (in_array($privilege, $this->user_privileges)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Send the Registration mail to the new user
     * with the dynamic twig templates vars
     * @param array $email_template_vars
     */
    protected function sendUpgradeWishMail(array $email_template_vars): void
    {
        // upgrade massage for the system admin
        // do anything else you need here, like send an email
        $message = new \Swift_Message('Kundenkonto Upgrade Wunsch bei Altmühlsee');

        // send a copy to
        if (null !== ($this->systemOptions->getCopiedReviverEmailAddress())) {
            $message->setCc($this->systemOptions->getCopiedReviverEmailAddress());
        }

        // if developer mode send mails to the developer
        if (null !== ($this->systemOptions->getTestEmailAddress())) {
            $message->setTo($this->systemOptions->getTestEmailAddress());
        } else {
            // real receiver email address from configuration
            $message->setTo($this->systemOptions->getSupportEmailAddress());
        }

        $message
            ->setFrom($this->systemOptions->getMailSystemAbsenceAddress())
            ->setBody(
                $this->renderView(
                // Email-Template templates/emails/user_wants_upgrade.html.twig
                    'emails/user_wants_upgrade.html.twig',
                    $email_template_vars
                ),
                'text/html'
            );

        $this->mailer->send($message);
    }

}
