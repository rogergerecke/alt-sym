<?php


namespace App\Controller\Admin;


use App\Entity\Advertising;
use App\Entity\Events;
use App\Entity\Hostel;
use App\Entity\Media;
use App\Entity\MediaGallery;
use App\Entity\RoomTypes;
use App\Entity\User;
use App\Repository\HostelRepository;
use App\Repository\UserRepository;
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

/**
 * Class UserDashboardController
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
     * UserDashboardController constructor.
     * @param Security $security
     * @param UserRepository $userRepository
     * @param HostelRepository $hostelRepository
     */
    public function __construct(Security $security, UserRepository $userRepository, HostelRepository $hostelRepository)
    {

        $this->security = $security;
        $this->userRepository = $userRepository;
        $this->hostelRepository = $hostelRepository;


        // if logged in get the logged in user id and account data
        if (null !== $this->security->getUser()) {
            $this->user_id = $this->security->getUser()->getId();
            $this->user_account = $this->userRepository->find($this->user_id);
            $this->user_privileges = $this->user_account->getUserPrivileges();

            // test case
            /* print_r($this->user_privileges);*/
        }


        // check if user have hostel
        if (null !== $this->hostelRepository->findOneBy(['user_id' => $this->user_id])) {
            $this->userHaveHostel = true;
        }


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

        // its not premium user show upgrade message
        if (!in_array('premium_account', $this->user_privileges)) {
            yield MenuItem::linktoDashboard('Upgrade to Premium', 'fa fa-star')
                ->setCssClass('bg-success text-white pl-2');
        }

        // link to the user account
        yield MenuItem::linkToCrud('Mein Konto', 'fa fa-id-card', User::class)
            ->setAction('detail')
            ->setEntityId($this->user_id);


        /* HOSTEL MENU > only show with the right user privileges */
        $check = ['free_account', 'base_account', 'premium_account',];
        if ($this->isUserHavePrivileges($check)) {

            yield MenuItem::section('Unterkunft-Einstellung', 'fa fa-tasks');

            // todo add show only from logged in user
            yield MenuItem::linkToCrud('Meine Unterkunft', 'fa fa-hotel', Hostel::class)
                ->setQueryParameter(
                    'user_id',
                    $this->user_id
                );

            yield MenuItem::linkToCrud('Unterkunft hinzufügen', 'fa fa-hotel', Hostel::class)
                ->setAction('new');

            // have the user hostel so he cant add rooms
            if ($this->userHaveHostel) {
                yield MenuItem::linkToCrud('Zimmer hinzufügen', 'fa fa-hotel', RoomTypes::class);
            }

            yield MenuItem::linkToCrud('Bilder Galerie', 'fa fa-image', MediaGallery::class)->setEntityId(1);
        }


        /* Media section */
        yield MenuItem::section('Media-Einstellung', 'fa fa-image');
        yield MenuItem::linktoRoute('Bilder Hochladen', 'fa fa-image', 'elfinder')
            ->setLinkTarget('_blank')
            ->setQueryParameter('instance', 'user');
        yield MenuItem::linkToCrud('Gallery', 'fa fa-image', MediaGallery::class);
        yield MenuItem::linkToCrud('Media', 'fa fa-image', Media::class);

        /* Marketing section */
        yield MenuItem::section('Marketing-Einstellung', 'fa fa-bullhorn');

            yield MenuItem::linkToCrud('Veranstaltung', 'fa fa-glass-cheers', Events::class);


        if ($this->isUserHavePrivileges(['leisure_offer'])) {
            yield MenuItem::linkToCrud('Freizeitangebot', 'fa fa-glass-cheers', Events::class);
        }


        /* Information section */
        yield MenuItem::section('Hilfe & Information', 'fa fa-info-circle');
        yield MenuItem::linktoRoute('Werbung', 'fa fa-question', 'static_site_contact');
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
                    MenuItem::section('--------------------'),
                    MenuItem::linkToLogout('Logout', 'fa fa-sign-out'),
                ]
            );
    }

    ######################################
    #
    #
    # Helper function
    #
    #
    #######################################

    /**
     * Check the user privileges
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


}