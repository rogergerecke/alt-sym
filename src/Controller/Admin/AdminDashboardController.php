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
use App\Entity\StaticSite;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

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

    public function __construct(Security $security)
    {

        $this->security = $security;

        $this->user_id = '1';
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {

        return parent::index();
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
        yield MenuItem::linktoRoute('Zur Website', 'fa fa-home', 'index');
        yield MenuItem::section('Navigator', 'fa fa-anchor');
        yield MenuItem::linkToCrud('Inhalts-Seiten', 'fa fa-columns', StaticSite::class);
        yield MenuItem::linkToCrud('Benutzer', 'fa fa-user', User::class)
            ->setController(AdminUserCrudController::class);
        yield MenuItem::linkToCrud('Veranstaltungen', 'fa fa-glass-cheers', Events::class);
        yield MenuItem::linkToCrud('Freizeitangebote', 'fa fa-spa', Leisure::class);
        yield MenuItem::linkToCrud('Werbebanner', 'fa fa-ad', Advertising::class);


        /* Hostel Manager section */
        [
            yield MenuItem::section('Hostel Manager', 'fa fa-house-user'),

            yield MenuItem::linkToCrud('Unterkünfte', 'fa fa-hotel', Hostel::class)
                ->setController(AdminHostelCrudController::class),

            yield MenuItem::linkToCrud('Zimmer', 'fa fa-hotel', Hostel::class)->setController(
                AdminHostelCrudController::class
            ),
            yield MenuItem::linkToCrud('Statistiken', 'fa fa-hotel', Hostel::class)->setController(
                AdminHostelCrudController::class
            ),
        ];


        /* Media Manager section */
        [
            yield MenuItem::section('Media Manager', 'fa fa-photo-video'),
            yield MenuItem::linktoRoute('Upload', 'fa fa-upload', 'elfinder'),
            yield MenuItem::linkToCrud('Dateien', 'fa fa-image', Media::class),
            yield MenuItem::linkToCrud('Gallery', 'fa fa-images', MediaGallery::class),
        ];

        /* System Config section */
        yield MenuItem::section('Grundeinstellung', 'fa fa-fan');
        yield MenuItem::linkToCrud('PLZ Regionen', 'fa fa-globe', Regions::class);
        yield MenuItem::linkToCrud('Hoteltypen', 'fa fa-caravan', AmenitiesTypes::class);
        yield MenuItem::linkToCrud('Zimmerausstattung ', 'fa fa-caravan', RoomAmenities::class);
        yield MenuItem::subMenu('Sprachen', 'fa fa-language')->setSubItems(
            [
                MenuItem::linkToCrud('Zimmerausstattung', 'fa fa-spell-check', RoomAmenitiesDescription::class),
            ]
        );
    }

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