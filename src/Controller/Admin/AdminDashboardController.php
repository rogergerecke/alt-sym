<?php

namespace App\Controller\Admin;

use App\Entity\AmenitiesTypes;
use App\Entity\Events;
use App\Entity\Hostel;
use App\Entity\Media;
use App\Entity\MediaGallery;
use App\Entity\Regions;
use App\Entity\RoomAmenitiesDescription;
use App\Entity\StaticSite;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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

        $this->user_id = $this->security->getUser()->getId();
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
            ->setTitle('<strong>Altmühlsee</strong>');
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
        yield MenuItem::linkToCrud('Seiten', 'fa fa-columns', StaticSite::class);
        yield MenuItem::linkToCrud('Benutzer', 'fa fa-user', User::class)
            ->setController(AdminUserCrudController::class);
        yield MenuItem::linkToCrud('Event', 'fa fa-glass-cheers', Events::class);


        /* Hostel section */
        yield MenuItem::section('Hostel Menu', 'fa fa-house-user');
        yield MenuItem::subMenu('Hostels', 'fa fa-hotel')->setSubItems(
            [
                MenuItem::linkToCrud('Hostel Manager', 'fa fa-hotel', Hostel::class)->setController(
                    AdminHostelCrudController::class
                ),
                MenuItem::linkToCrud('Hostel Typen', 'fa fa-caravan', AmenitiesTypes::class),
            ]
        );

        /* System config section */
        yield MenuItem::section('System Einstellung', 'fa fa-fan');
        yield MenuItem::linkToCrud('Regionen', 'fa fa-globe', Regions::class);
        yield MenuItem::subMenu('Übersetzung', 'fa fa-language')->setSubItems(
            [
                MenuItem::linkToCrud('Zimmerausstattung', 'fa fa-spell-check', RoomAmenitiesDescription::class),
            ]
        );

        /* Media Manager section */
        yield MenuItem::section('Media Manager', 'fa fa-photo-video');
        yield MenuItem::subMenu('Gallery', 'fa fa-tags')->setSubItems(
            [
                MenuItem::linkToCrud('Media', 'fa fa-image', Media::class),
                MenuItem::linkToCrud('Media Gallery', 'fa fa-images', MediaGallery::class),
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