<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\UserPrivilegesTypesRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
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
        return $crud->setPageTitle(Crud::PAGE_INDEX, 'Benutzer');
    }

    public function configureFields(string $pageName): iterable
    {
        $email = TextField::new('email');
        $password = TextField::new('password')
            ->setFormType(PasswordType::class)
            ->setFormTypeOption('empty_data', '')
            ->setRequired(false);

        $partner_id = IntegerField::new('partner_id', 'Kundennummer')
            ->setHelp('Die Kundennummer sollte man nicht ändern sie wird auf Rechnung verwendet');

        $name = TextField::new('name', 'Ganzer Name');
        $status = BooleanField::new('status', 'Account Online');
        $id = IntegerField::new('id', 'ID');

        /* Create user privileges dropdown*/
        $user_privileges = CollectionField::new('user_privileges', 'Benutzer Rechte')->setHelp(
            'Die Benutzer Rechte sind kombinierbar: Free Account + Werbebanner'
        )
            ->setEntryType(ChoiceType::class)
            ->setFormTypeOptions(
                [
                    'entry_options' => [
                        'choices'  => [
                            $this->buildUserPrivilegesOptions(),
                        ],
                        'label'    => false,
                        'group_by' => 'id',
                    ],
                ]
            );

        $hostel_name = AssociationField::new('hostels');

        switch ($pageName) {
            case Crud::PAGE_INDEX:
                return [
                    $id,
                    $email,
                    $partner_id,
                    $name,
                    $user_privileges,
                    $hostel_name,
                    $status,
                ];
                break;
            case Crud::PAGE_NEW:
            case Crud::PAGE_EDIT:
                return [
                    $name,
                    $partner_id,
                    $email,
                    $user_privileges,
                    $password,
                    $status,
                ];
                break;
            case Crud::PAGE_DETAIL:
                return [
                    $id,
                    $name,
                    $user_privileges,
                    $partner_id,
                    $email,
                    $password,
                    $status,
                ];
                break;
        }
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

        foreach ($privileges as $privilege) {
            $options[$privilege->getName()] = $privilege->getCode();
        }

        return $options;
    }
}
