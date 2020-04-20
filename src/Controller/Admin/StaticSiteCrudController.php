<?php

namespace App\Controller\Admin;

use App\Entity\StaticSite;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextAreaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class StaticSiteCrudController extends AbstractCrudController
{
    public static $entityFqcn = StaticSite::class;

    public function configureCrud(Crud $crud): Crud
    {
        return $crud;
    }

    public function configureFields(string $pageName): iterable
    {
        $heading = TextAreaField::new('heading');
        $content = TextAreaField::new('content');
        $name = TextAreaField::new('name');
        $meta_title = TextAreaField::new('meta_title');
        $meta_description = TextAreaField::new('meta_description');
        $url = UrlField::new('url');
        $id = IntegerField::new('id', 'ID');
        $status = BooleanField::new('status');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$heading, $content, $name, $meta_title, $meta_description, $status, $url];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $name, $meta_title, $meta_description, $heading, $content, $status, $url];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$heading, $content, $name, $meta_title, $meta_description, $url];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$heading, $content, $name, $meta_title, $meta_description, $url];
        }
    }
}
