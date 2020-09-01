<?php

namespace App\Controller\Admin;

use App\Entity\AmenitiesTypes;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

/**
 * Class AmenitiesTypesCrudController
 * @package App\Controller\Admin
 */
class AmenitiesTypesCrudController extends AbstractCrudController
{
    /**
     * @var string
     */
    public static $entityFqcn = AmenitiesTypes::class;

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
                return $action->setIcon('fa fa-file-alt')->setLabel('Zimmerausstattung erstellen');
            });
    }

    /**
     * @param string $pageName
     * @return iterable
     */
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

    /**
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return self::$entityFqcn;
    }
}
