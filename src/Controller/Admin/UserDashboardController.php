<?php


namespace App\Controller\Admin;


use App\Entity\Advertising;
use App\Entity\Events;
use App\Entity\Hostel;
use App\Entity\Media;
use App\Entity\MediaGallery;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

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

    public function __construct(Security $security)
    {

       /* $this->security = $security;

        $this->user_id = $this->security->getUser()->getId();*/

        $this->user_id = '1';
    }

    /**
     * @Route("/user", name="user")
     */
    public function index(): Response
    {

        return parent::index();
    }

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


    public function configureMenuItems(): iterable
    {

        yield MenuItem::linktoDashboard('Upgrade to Premium','fa fa-star');

        yield MenuItem::linkToCrud('Mein Konto', 'fa fa-id-card', User::class)
            ->setAction('detail')
            ->setEntityId($this->user_id);

        /* Hostel menu */
        yield MenuItem::section('Unterkunft-Einstellung', 'fa fa-tasks');

        // todo add show only from logged in user
        yield MenuItem::linkToCrud('Meine Unterkunft', 'fa fa-hotel', Hostel::class)
            ->setQueryParameter(
                'user_id',
                $this->user_id
            );
        yield MenuItem::linkToCrud('Unterkunft hinzufügen', 'fa fa-hotel', Hostel::class)
            ->setAction('new');
        yield MenuItem::linkToCrud('Zimmer hinzufügen', 'fa fa-hotel', Hostel::class);
        yield MenuItem::linkToCrud('Bilder Galerie', 'fa fa-image', MediaGallery::class);


        /* Media section */
        yield MenuItem::section('Media-Einstellung', 'fa fa-image');
        yield MenuItem::linkToCrud('Galerie bearbeiten', 'fa fa-image', MediaGallery::class);
        yield MenuItem::linkToCrud('Bilder bearbeiten', 'fa fa-image', Media::class);

        /* Marketing section */
        yield MenuItem::section('Marketing-Einstellung', 'fa fa-bullhorn');
        yield MenuItem::linkToCrud('Veranstaltung', 'fa fa-glass-cheers', Events::class);// todo upgrade info
        yield MenuItem::linkToCrud('Freizeitangebot', 'fa fa-glass-cheers', Events::class);
        yield MenuItem::linkToCrud('Banner Werbung', 'fa fa-ad', Advertising::class);


        /* Information section */
        yield MenuItem::section('Hilfe & Information', 'fa fa-info-circle');
        yield MenuItem::linkToUrl('Werbung', 'fa fa-question', '/');
        yield MenuItem::linkToUrl('Anleitung Bild bearbeiten ', 'fa fa-question', '/');
        yield MenuItem::linktoRoute('Preise', 'fa fa-question', 'static_site_entry');
        yield MenuItem::linktoRoute('Impressum', 'fa fa-question', 'static_site_imprint');
        yield MenuItem::linktoRoute('Datenschutz', 'fa fa-question', 'static_site_privacy');
        yield MenuItem::linktoRoute('Kontakt', 'fa fa-question', 'static_site_contact');
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