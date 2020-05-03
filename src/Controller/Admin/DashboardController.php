<?php

namespace App\Controller\Admin;

use App\Entity\AmenitiesTypes;
use App\Entity\Hostel;
use App\Entity\Regions;
use App\Entity\RoomAmenitiesDescription;
use App\Entity\StaticSite;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Menu\MenuItemInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class DashboardController extends AbstractDashboardController
{
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
            ->setDateFormat('ddMMyyyy');
    }

    /* Global Admin Menu */
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Seiten', 'fa fa-columns', StaticSite::class);
        yield MenuItem::linkToCrud('Benutzer', 'fa fa-user', User::class);
        yield MenuItem::linkToCrud('Regionen', 'fa fa-globe', Regions::class);

        /* Hostel section */
        yield MenuItem::section('Hotel Gruppe', 'fa fa-house-user');
        yield MenuItem::subMenu('Hotels')->setSubItems(
            [
                MenuItem::linkToCrud('Hostels', 'fa fa-hotel', Hostel::class),
                MenuItem::linkToCrud('Hostel Typen', 'fa fa-caravan', AmenitiesTypes::class),
            ]
        );

        /* System config section */
        yield MenuItem::section('System Einstellung', 'fa fa-fan');
        yield MenuItem::subMenu('Übersetzung', 'fa fa-language')->setSubItems(
            [
                MenuItem::linkToCrud('Zimmerausstattung', 'fa fa-spell-check', RoomAmenitiesDescription::class),
            ]
        );

        /* Media Manager section */
        yield MenuItem::section('Media Manager', 'fa fa-photo-video');
        yield MenuItem::subMenu('Gallery', 'fa fa-tags')->setSubItems(
            [
                MenuItem::linkToCrud('Media', 'fa fa-image', MediaCrudController::class),
                MenuItem::linkToCrud('Media Gallery', 'fa fa-images', MediaGalleryCrudController::class),
            ]
        );
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
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
            ->addMenuItems([
                MenuItem::linkToRoute('Mein Profil', 'fa fa-id-card', 'admin_user_profil'),
                /*MenuItem::linkToRoute('Settings', 'fa fa-user-cog', '...', ['...' => '...']),*/
                MenuItem::section('------'),
                MenuItem::linkToLogout('Logout', 'fa fa-sign-out'),
            ]);
    }

}