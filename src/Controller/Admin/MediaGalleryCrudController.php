<?php

namespace App\Controller\Admin;

use App\Entity\MediaGallery;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class MediaGalleryCrudController extends AbstractCrudController
{
    public static $entityFqcn = MediaGallery::class;


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('sort'),
            BooleanField::new('status'),
        ];
    }

}
