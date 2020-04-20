<?php

namespace App\Controller\Admin;

use App\Entity\HostelTypes;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class HostelTypesCrudController extends AbstractCrudController
{
    public static $entityFqcn = HostelTypes::class;

    public function configureCrud(Crud $crud): Crud
    {
        return $crud;
    }

    public function configureFields(string $pageName): iterable
    {
        $name = TextField::new('name');
        $type_id = IntegerField::new('type_id');
        $active = IntegerField::new('active');
        $sort = IntegerField::new('sort');
        $id = IntegerField::new('id', 'ID');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $name, $type_id, $active, $sort];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $name, $type_id, $active, $sort];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$name, $type_id, $active, $sort];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$name, $type_id, $active, $sort];
        }
    }
}
