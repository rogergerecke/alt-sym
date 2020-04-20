<?php

namespace App\Controller\Admin;

use App\Entity\Regions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RegionsCrudController extends AbstractCrudController
{
    public static $entityFqcn = Regions::class;

    public function configureCrud(Crud $crud): Crud
    {
        return $crud;
    }

    public function configureFields(string $pageName): iterable
    {
        $name = TextField::new('name');
        $zipcode = IntegerField::new('zipcode');
        $active = IntegerField::new('active');
        $regions_id = IntegerField::new('regions_id');
        $id = IntegerField::new('id', 'ID');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $name, $zipcode, $active, $regions_id];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $name, $zipcode, $active, $regions_id];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$name, $zipcode, $active, $regions_id];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$name, $zipcode, $active, $regions_id];
        }
    }
}
