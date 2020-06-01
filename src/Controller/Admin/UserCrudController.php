<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserCrudController extends AbstractCrudController
{
    public static $entityFqcn = User::class;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var Request
     */
    private $request;


    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;

    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud;
    }


    /**
     * Manipulate the viewed action in User entity
     *
     * @param Actions $actions
     * @return Actions
     */
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_DETAIL, Action::DELETE)
            ->remove(Crud::PAGE_DETAIL, Action::INDEX);
    }

    public function configureFields(string $pageName): iterable
    {

        $id = IdField::new('id');
        $email = TextField::new('email');

        $password = TextField::new('password')->setFormType(PasswordType::class);

        $partner_id = IntegerField::new('partner_id', 'Kundennummer');
        $name = TextField::new('name', 'Ganzer Name');
        $status = BooleanField::new('status', 'Account Online');
        $id = IntegerField::new('id', 'ID');

        $hostels = ArrayField::new('hostels');


        if (Crud::PAGE_INDEX === $pageName) {
            return [
                $id,
                $email,
                $partner_id,
                $name,
                /* $hostels*/
            ];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [

                $name,
                $partner_id,
                $email,
                $password,
                $status,
                $id
            ];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [

            ];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [
                $email,
                $password,
                $name,
                /* $hostels*/
            ];
        }
    }

    public static function getEntityFqcn(): string
    {
        return self::$entityFqcn;
    }

}
