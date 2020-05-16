<?php

namespace App\Controller\Admin;

use App\Entity\StaticSite;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;


class StaticSiteCrudController extends AbstractCrudController
{
    public static $entityFqcn = StaticSite::class;

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityPermission('ROLE_ADMIN')
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig')
            ->setPageTitle('index', 'Die Inhaltsseiten')
            ->setHelp(
                'index',
                'Die Inhaltsseiten dienen zum anlegen von statischen Inhalten wie z.b. das Impressum oder die AGB'
            );
    }

    public function configureFields(string $pageName): iterable
    {
        $heading = TextField::new('heading', 'Überschrift');
        $panel = FormField::addPanel('Inhalts Seite Bearbeiten');

        /* ckeditor include by FOS plugin */
        $content = TextareaField::new('content','Seiten Inhalt')
            ->setFormType(CKEditorType::class);

        $route = TextField::new('route', '@Route annotation');
        $meta_title = TextField::new('meta_title', 'Meta Title');
        $meta_description = TextField::new('meta_description', 'Meta Beschreibung');
        $url = TextField::new('url', 'SEO Url');
        $id = IntegerField::new('id', 'ID');
        $status = BooleanField::new('status');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$heading, $route, $status, $url];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $route, $meta_title, $meta_description, $heading, $content, $status, $url];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$heading, $content, $route, $meta_title, $meta_description, $url];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$panel, $heading, $content, $route, $meta_title, $meta_description, $url];
        }
    }

    public static function getEntityFqcn(): string
    {
        return self::$entityFqcn;
    }
}
