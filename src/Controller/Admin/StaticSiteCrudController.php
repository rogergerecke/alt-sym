<?php

namespace App\Controller\Admin;

use App\Entity\StaticSite;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
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
        $heading = TextAreaField::new('heading','Überschrift');
        $content = TextField::new('content')->addCssClass('ckeditor');
        $name = TextAreaField::new('name','Route');
        $meta_title = TextAreaField::new('meta_title','Meta Title');
        $meta_description = TextAreaField::new('meta_description','Meta Beschreibung');
        $url = UrlField::new('url','SEO Url');
        $id = IntegerField::new('id', 'ID');
        $status = BooleanField::new('status');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$heading, $name, $status, $url];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $name, $meta_title, $meta_description, $heading, $content, $status, $url];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$heading, $content, $name, $meta_title, $meta_description, $url];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$heading, $content, $name, $meta_title, $meta_description, $url];
        }
    }

    public static function getEntityFqcn(): string
    {
        return self::$entityFqcn;
    }
}
