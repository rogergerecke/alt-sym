<?php

namespace App\Controller\Admin;

use App\Entity\RoomAmenitiesDescription;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RoomAmenitiesDescriptionCrudController extends AbstractCrudController
{
    public static $entityFqcn = RoomAmenitiesDescription::class;


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('ra_id'),
            TextField::new('name'),
            TextEditorField::new('description'),
            TextField::new('lang')
        ];
    }

    public static function getEntityFqcn(): string
    {
       return self::$entityFqcn;
    }
}
