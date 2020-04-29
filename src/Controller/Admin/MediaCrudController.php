<?php

namespace App\Controller\Admin;

use App\Entity\Media;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class MediaCrudController extends AbstractCrudController
{
    public static $entityFqcn = Media::class;


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('file'),
            TextEditorField::new('type'),
        ];
    }

    public static function getEntityFqcn(): string
    {
        return self::$entityFqcn;
    }
}
