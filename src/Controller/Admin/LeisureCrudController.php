<?php

namespace App\Controller\Admin;

use App\Entity\Leisure;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class LeisureCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Leisure::class;
    }

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
}
