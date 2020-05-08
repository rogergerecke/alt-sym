<?php

namespace App\Controller\Admin;

use App\Entity\Media;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class MediaCrudController extends AbstractCrudController
{
    public static $entityFqcn = Media::class;

    public function configureCrud(Crud $crud): Crud
    {
        return $crud;
    }

    public function configureFields(string $pageName): iterable
    {

        $id = IdField::new('id');
        $file = TextField::new('file');
        $type = TextField::new('type');
        $class = TextField::new('class');
        $user_id = TextField::new('user_id');
        $status = BooleanField::new('status');

        // output fields by page
        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $file, $type, $class, $user_id, $status];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $file, $type, $class, $user_id, $status];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$id, $file, $type, $class, $user_id, $status];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$id, $file, $type, $class, $user_id, $status];
        }
    }

    public static function getEntityFqcn(): string
    {
        return self::$entityFqcn;
    }
}
