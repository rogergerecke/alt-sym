<?php

namespace App\Controller\Admin;

use App\Entity\SystemOptions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SystemOptionsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SystemOptions::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setHelp('edit', 'Nehmen Sie hier nur Einstellung vor wenn Sie wissen was Sie tun.')
            ->setHelp('new', 'Nehmen Sie hier nur Einstellung vor wenn Sie wissen was Sie tun.')
            ->setHelp('index', 'Nehmen Sie hier nur Einstellung vor wenn Sie wissen was Sie tun.');
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Erklärender Name')->setHelp('Der Name der Variable zum verständnis was Ihre Aufgabe ist.'),
            TextareaField::new('description', 'Beschreibung')->setHelp('Eine Beschreibung der Variable und was Ihre aufgabe ist.'),
            TextField::new('var_name', 'Var-Name')->setHelp('Der eigentliche Variable Name der im System dann verfügbar ist in GROSSBUCHSTABEN_UND_UNTERSTRICHEN'),
            TextField::new('value', 'Var-Value')->setHelp('Der wert der Variable')
        ];
    }
}
