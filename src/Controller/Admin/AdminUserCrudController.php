<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AdminUserCrudController extends AbstractCrudController
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
        $partner_id = IntegerField::new('partner_id', 'Kundennummer');
        $name = TextField::new('name', 'Ganzer Name');
        $status = BooleanField::new('status', 'Account Online');
        $id = IntegerField::new('id', 'ID');

        $hostel_name = TextField::new('hostel_name');

        switch ($pageName) {
            case Crud::PAGE_INDEX:
                return [
                    $id,
                    $email,
                    $partner_id,
                    $name,
                    $hostel_name,
                    $status
                ];
                break;
            case Crud::PAGE_NEW:
            case Crud::PAGE_EDIT:
            return [
                $name,
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
}
