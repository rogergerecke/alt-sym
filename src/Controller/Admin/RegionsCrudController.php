<?php

namespace App\Controller\Admin;

use App\Entity\Regions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RegionsCrudController extends AbstractCrudController
{
    public static $entityFqcn = Regions::class;

    public function configureCrud(Crud $crud): Crud
    {
        return $crud;
    }

    /**
     * Modify the action button text and icon
     * @param Actions $actions
     * @return Actions
     */
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // rewrite the Action button text
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setIcon('fa fa-file-alt')->setLabel('Region anlegen');
            });
    }

    public function configureFields(string $pageName): iterable
    {
        // create fields
        $regions_id = IntegerField::new('regions_id');
        $name = TextField::new('name');
        $zipcode = IntegerField::new('zipcode', 'PLZ');
        $status = BooleanField::new('status', 'On / Off');

        // output fields by page
        if (Crud::PAGE_INDEX === $pageName) {
            return [$regions_id, $name, $zipcode, $status];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$regions_id, $name, $zipcode, $status];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$name, $zipcode, $status, $regions_id];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$name, $zipcode, $status, $regions_id];
        }
    }

    public static function getEntityFqcn(): string
    {
        return self::$entityFqcn;
    }
}
