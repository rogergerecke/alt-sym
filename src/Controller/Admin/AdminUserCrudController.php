<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\UserPrivilegesTypesRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Routing\Annotation\Route;

class AdminUserCrudController extends AbstractCrudController
{
    public static $entityFqcn = User::class;

    /**
     * @var UserPrivilegesTypesRepository
     */
    private $privilegesTypesRepository;

    public function __construct(UserPrivilegesTypesRepository $privilegesTypesRepository)
    {
        $this->privilegesTypesRepository = $privilegesTypesRepository;
    }


    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setPageTitle(Crud::PAGE_INDEX,'Benutzer');
    }

    public function configureFields(string $pageName): iterable
    {
        $email = TextField::new('email');
        $password = TextField::new('password');
        $partner_id = IntegerField::new('partner_id', 'Kundennummer');
        $name = TextField::new('name', 'Ganzer Name');
        $status = BooleanField::new('status', 'Account Online');
        $id = IntegerField::new('id', 'ID');

        /* Create user privileges dropdown*/
        $user_privileges = CollectionField::new('user_privileges', 'Benutzer Rechte')->setHelp(
            'Dem Benutzer rechte zuweisen'
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

        $hostel_name = TextField::new('hostel_name');

        switch ($pageName) {
            case Crud::PAGE_INDEX:
                return [
                    $id,
                    $email,
                    $partner_id,
                    $name,
                    $user_privileges,
                    $hostel_name,
                    $status
                ];
                break;
            case Crud::PAGE_NEW:
            case Crud::PAGE_EDIT:
            return [
                $name,
                $user_privileges,
                $partner_id,
                $email,
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

    public static function getEntityFqcn(): string
    {
        return self::$entityFqcn;
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
