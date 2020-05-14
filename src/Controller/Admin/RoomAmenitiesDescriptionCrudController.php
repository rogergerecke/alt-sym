<?php

namespace App\Controller\Admin;

use App\Entity\RoomAmenitiesDescription;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RoomAmenitiesDescriptionCrudController extends AbstractCrudController
{
    public static $entityFqcn = RoomAmenitiesDescription::class;

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setHelp('index','Die Tabelle mit den Übersetzungs Texten der Zimmerausstattung');
    }

    public function configureFields(string $pageName): iterable
    {
        $id = IdField::new('id');
        $ra_id = TextField::new('ra_id');
        $name = TextField::new('name');
        $description = TextField::new('description','Beschreibung');
        $lang = TextField::new('lang','Lang Code');

        switch ($pageName) {
            case Crud::PAGE_INDEX:
            case Crud::PAGE_DETAIL:
                return [
                    $id,
                    $ra_id,
                    $name,
                    $description,
                    $lang,
                ];
                break;
            case Crud::PAGE_NEW:
            case Crud::PAGE_EDIT:
                return [
                    $ra_id,
                    $name,
                    $description,
                    $lang,
                ];
                break;
        }
    }


    public static function getEntityFqcn(): string
    {
        return self::$entityFqcn;
    }
}
