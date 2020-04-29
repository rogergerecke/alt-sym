<?php

namespace App\Controller\Admin;

use App\Entity\AmenitiesTypes;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AmenitiesTypesCrudController extends AbstractCrudController
{
    public static $entityFqcn = AmenitiesTypes::class;


    public function configureFields(string $pageName): iterable
    {
        $name = TextField::new('name');
        $type_id = IntegerField::new('amenities_id');
        $active = BooleanField::new('status');
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

        return [];
    }

    public static function getEntityFqcn(): string
    {
        return self::$entityFqcn;
    }
}
