<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static $entityFqcn = User::class;

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
        $email = TextField::new('email');
        $password = TextField::new('password');
        $partner_id = IntegerField::new('partner_id','Kundennummer');
        $name = TextField::new('name','Ganzer Name');
        $status = BooleanField::new('status','Account Online');
        $id = IntegerField::new('id', 'ID');

        $hostel_name = TextField::new('hostel_name');

        if (Crud::PAGE_INDEX === $pageName) {
            return [
                $id,
                $email,
                $partner_id,
                $name,
                $hostel_name
            ];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [

                $name,
                $partner_id,
                $email,
                $password,
                $status
            ];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [

            ];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [
                $email,
                $password,
                $name
            ];
        }
    }

    public static function getEntityFqcn(): string
    {
        return self::$entityFqcn;
    }
}
