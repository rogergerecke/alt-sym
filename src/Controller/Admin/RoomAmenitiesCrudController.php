<?php

namespace App\Controller\Admin;

use App\Entity\RoomAmenities;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RoomAmenitiesCrudController extends AbstractCrudController
{
    public static $entityFqcn = RoomAmenities::class;

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
    public static function getEntityFqcn(): string
    {
        return self::$entityFqcn;
    }


}
