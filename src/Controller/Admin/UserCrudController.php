<?php

namespace App\Controller\Admin;

use App\Entity\User;
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

    public function configureFields(string $pageName): iterable
    {
        $email = TextField::new('email');
        $password = TextField::new('password');
        $partner_id = IntegerField::new('partner_id');
        $name = TextField::new('name');
        $status = BooleanField::new('status','Status');
        $id = IntegerField::new('id', 'ID');
        $roles = TextField::new('roles');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $email, $partner_id, $name, $status];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $email, $roles, $password, $partner_id, $name, $status];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$email, $password, $partner_id, $name, $status];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$email, $password, $partner_id, $name, $status];
        }
    }
}
