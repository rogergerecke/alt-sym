<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\UserPrivilegesTypesRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

class AdminUserCrudController extends AbstractCrudController
{
    public static $entityFqcn = User::class;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var UserPrivilegesTypesRepository
     */
    private $privilegesTypesRepository;
    /**
     * @var object|\Symfony\Component\Security\Core\User\UserInterface|null
     */
    private $user;
    /**
     * @var Security
     */
    private $security;
    /**
     * @var string|null
     */
    private $password;

    /**
     * AdminUserCrudController constructor.
     * @param UserPrivilegesTypesRepository $privilegesTypesRepository
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param Security $security
     */
    public function __construct(
        UserPrivilegesTypesRepository $privilegesTypesRepository,
        UserPasswordEncoderInterface $passwordEncoder,
        Security $security
    ) {
        $this->privilegesTypesRepository = $privilegesTypesRepository;
        $this->passwordEncoder = $passwordEncoder;


        if (null !== $security->getUser()) {
            $this->password = $security->getUser()->getPassword();
        }
    }

    public static function getEntityFqcn(): string
    {
        return self::$entityFqcn;
    }


    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Benutzer')
            ->setPageTitle(Crud::PAGE_EDIT, 'Benutzer bearbeiten');
    }

    public function configureFields(string $pageName): iterable
    {
        $id = IntegerField::new('id');
        $email = TextField::new('email');
        $password = TextField::new('password')
            ->setFormType(PasswordType::class)
            ->setFormTypeOption('empty_data', '')
            ->setRequired(false)
            ->setHelp('Wenn das Password nicht geändert werden soll feld leer lassen.');

        $partner_id = IntegerField::new('partner_id', 'Kundennummer')
            ->setFormTypeOption('disabled', true);

        $name = TextField::new('name', 'Vorname Name');
        $status = BooleanField::new('status', 'Status On/Off');

        /* Create user privileges dropdown*/
        $user_privileges = CollectionField::new('user_privileges', 'Benutzer Rechte')->setHelp(
            'Die Benutzer Rechte sind kombinierbar: Free Account + Werbebanner'
        )
            ->setEntryType(ChoiceType::class)
            ->setFormTypeOptions(
                [
                    'entry_options' => [
                        'choices' => [
                            $this->buildUserPrivilegesOptions(),
                        ],
                        'label' => false,
                        'group_by' => 'id',
                    ],
                ]
            );

        // have a user made changes on his account
        $is_user_made_changes = BooleanField::new('is_user_made_changes', 'Änderung?')
            ->setHelp('Hat der Kunde änderung an diesem Konto vorgenommen?')
            ->setSortable(true);

        // wont the user a upgrade
        $is_he_wants_upgrade = BooleanField::new('is_he_wants_upgrade', 'Upgrade?')
            ->setHelp('Möchte dieser Kunde ein Upgrade machen?')
            ->setSortable(true);

        $create_at = DateTimeField::new('createAt', 'Registrierung-Datum')
            ->setFormTypeOption('disabled', true);
        $run_time = DateTimeField::new('run_time', 'Vertragslaufzeit');

        /* Extras */
        $hostel_name = AssociationField::new('hostels', 'Unterkunft Counter')
            ->setHelp('Wie viele Unterkünfte hat er angelegt');

        $panel_user_rights = FormField::addPanel('Benutzer Rechte')->setHelp('Die Rechte des Benutzer-Kontos');
        $panel_information = FormField::addPanel('Admin Information')->setHelp(
            'Wenn Sie die Änderung überprüft haben oder das Upgrade durchgeführt wurde entfernen Sie den Hacken damit der Kunde nicht mehr hervorgehoben wird.'
        );

        switch ($pageName) {
            case Crud::PAGE_INDEX:
                return [
                    $id,
                    $email,
                    $partner_id,
                    $name,
                    $status,
                    $is_user_made_changes,
                    $is_he_wants_upgrade,
                    $create_at,
                    $run_time,
                ];
                break;
            case Crud::PAGE_NEW:
            case Crud::PAGE_EDIT:
                return [
                    $partner_id,
                    $name,
                    $email,
                    $password,
                    $create_at,
                    $run_time,
                    $panel_user_rights,
                    $user_privileges,
                    $status,
                    $panel_information,
                    $is_user_made_changes,
                    $is_he_wants_upgrade,

                ];
                break;
            case Crud::PAGE_DETAIL:
                return [
                    $id,
                    $email,
                    $partner_id,
                    $name,
                    $status,
                    $is_user_made_changes,
                    $is_he_wants_upgrade,
                    $password,
                    $hostel_name,
                ];
                break;
        }
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->add(Crud::PAGE_EDIT, Action::DELETE)
            ;
    }


    /**
     * Password generation on password entity update over Symfony core
     * @param EntityManagerInterface $entityManager
     * @param $entityInstance
     */
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {


        // set new password with encoder interface
        if (method_exists($entityInstance, 'setPassword')) {
            $clearPassword = $this->get('request_stack')->getCurrentRequest()->request->all('User')['password'];

            // if user password not change save the old one
            if (empty($clearPassword)) {
                $entityInstance->setPassword($this->getUser()->getPassword());
            } else {
                $encodedPassword = $this->passwordEncoder->encodePassword($this->getUser(), $clearPassword);
                $entityInstance->setPassword($encodedPassword);
            }
        }

        parent::updateEntity($entityManager, $entityInstance);
    }



    #############################
    #
    # Helper function protected
    #
    #############################

    protected function buildUserPrivilegesOptions()
    {
        $options = [];

        $privileges = $this->privilegesTypesRepository->findBy(['status' => true]);

        $oneYear = date('Y-m-d', strtotime(date("Y-m-d", mktime())." + 365 day"));
        foreach ($privileges as $privilege) {
            $options[$privilege->getName()] = $privilege->getCode();
        }

        return $options;
    }
}
