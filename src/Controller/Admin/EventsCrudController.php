<?php

namespace App\Controller\Admin;

use App\Entity\Events;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class EventsCrudController extends AbstractCrudController
{
    public static $entityFqcn = Events::class;

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
    }

    public function configureFields(string $pageName): iterable
    {

        return [
            IdField::new('id'),
            TextField::new('title'),
            TextareaField::new('description','Beschreibung')->setFormType(CKEditorType::class),
        ];
    }

    public static function getEntityFqcn(): string
    {
        return self::$entityFqcn;
    }
}
