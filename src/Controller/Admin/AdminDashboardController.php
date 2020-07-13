<?php

namespace App\Controller\Admin;

use App\Entity\Advertising;
use App\Entity\AmenitiesTypes;
use App\Entity\Events;
use App\Entity\Hostel;
use App\Entity\Leisure;
use App\Entity\Media;
use App\Entity\MediaGallery;
use App\Entity\Regions;
use App\Entity\RoomAmenities;
use App\Entity\RoomAmenitiesDescription;
use App\Entity\RoomTypes;
use App\Entity\StaticSite;
use App\Entity\SystemOptions;
use App\Entity\User;
use App\Repository\AdminMessageRepository;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;


use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;

class AdminDashboardController extends AbstractDashboardController
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
     * @var CrudUrlGenerator
     */
    private $crudUrlGenerator;

    private $routeBuilder;
    /**
     * @var string
     */
    private $user_route;
    /**
     * @var AdminMessageRepository
     */
    private $adminMessageRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var User[]
     */
    private $user_upgrade;

    public function __construct(
        Security $security,
        AdminMessageRepository $adminMessageRepository,
        UserRepository $userRepository
    ) {

        $this->security = $security;
        $this->adminMessageRepository = $adminMessageRepository;
        $this->userRepository = $userRepository;


        // build the user id for the my account link
        if (null !== $this->security->getUser()) {
            $this->user_id = $this->security->getUser()->getId();
        }

    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {

        // get the admin messages
        $admin_messages = $this->adminMessageRepository->findAll();

        // get upgrade wishes
        if ($user_upgrade = $this->userRepository->findBy(['isHeWantsUpgrade' => 1])) {
            $this->user_upgrade = $user_upgrade;
        }

        return $this->render(
            'bundles/EasyAdmin/start_admin.html.twig',
            [
                'has_content_subtitle' => false,
                'admin_messages'       => $admin_messages,
                'user_upgrade'         => $user_upgrade,
            ]
        );
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('<strong>Admin Altmühlsee</strong>');
    }

    public function configureCrud(): Crud
    {
        return Crud::new()
            ->setDateFormat('dd.MM.yyyy')
            ->setEntityPermission('ROLE_ADMIN');
    }


    /* Global Admin Menu */
    public function configureMenuItems(): iterable
    {
        /* Link to Homepage */
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-tachometer-alt');
        yield MenuItem::linktoRoute('Zur Seite', 'fa fa-home', 'index');

        /* Hostel Manager section */
        [
            yield MenuItem::section('Manager', 'fa fa-house-user'),

            yield MenuItem::linkToCrud('Benutzerkonten', 'fa fa-user', User::class)
                ->setController(AdminUserCrudController::class),

            yield MenuItem::linkToCrud('Unterkünfte', 'fa fa-hotel', Hostel::class)
                ->setController(AdminHostelCrudController::class),

            yield MenuItem::linkToCrud('Zimmer hinzufügen', 'fa fa-hotel', RoomTypes::class)->setController(
                AdminRoomTypesCrudController::class
            ),

            yield MenuItem::linkToCrud('Statistiken', 'fa fa-hotel', Hostel::class)->setController(
                AdminHostelCrudController::class
            ),
        ];

        /* Ads */
        yield MenuItem::section('Werbung', 'fa fa-anchor');
        yield MenuItem::linkToCrud('Veranstaltungen', 'fa fa-glass-cheers', Events::class);
        yield MenuItem::linkToCrud('Freizeitangebote', 'fa fa-spa', Leisure::class);
        yield MenuItem::linkToCrud('Werbebanner', 'fa fa-ad', Advertising::class);


        /* Media Manager section */
        [
            yield MenuItem::section('Media Manager', 'fa fa-photo-video'),
            yield MenuItem::linktoRoute('Upload', 'fa fa-upload', 'elfinder')
                ->setQueryParameter('instance', 'admin'),
            yield MenuItem::linkToCrud('Dateien', 'fa fa-image', Media::class),
            yield MenuItem::linkToCrud('Gallery', 'fa fa-images', MediaGallery::class),
        ];

        /* System Config section */
        yield MenuItem::section('System', 'fa fa-desktop');
        yield MenuItem::linkToCrud('Einstellung', 'fa fa-fan', SystemOptions::class);
        yield MenuItem::linkToCrud('Seiten', 'fa fa-columns', StaticSite::class);
        yield MenuItem::linkToCrud('Orte', 'fa fa-globe', Regions::class);
        yield MenuItem::linkToCrud('Unterkunftstypen', 'fa fa-caravan', AmenitiesTypes::class);
        yield MenuItem::linkToCrud('Zimmerausstattung ', 'fa fa-caravan', RoomAmenities::class);
        yield MenuItem::subMenu('Mehrsprachigkeit', 'fa fa-language')->setSubItems(
            [
                MenuItem::linkToCrud('Zimmerausstattung', 'fa fa-spell-check', RoomAmenitiesDescription::class),
            ]
        );
    }


    /**
     * Create the User Flyout menu over the top right corner
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
                    /*MenuItem::linkToRoute('Settings', 'fa fa-user-cog', '...', ['...' => '...']),*/
                    MenuItem::section('------'),
                    MenuItem::linkToLogout('Logout', 'fa fa-sign-out'),
                ]
            );
    }
}
