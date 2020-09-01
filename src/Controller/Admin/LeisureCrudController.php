<?php

namespace App\Controller\Admin;

use App\Entity\Leisure;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

/**
 * Class LeisureCrudController
 * @package App\Controller\Admin
 */
class LeisureCrudController extends AbstractCrudController
{

    /**
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return Leisure::class;
    }

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
                return $action->setIcon('fa fa-file-alt')->setLabel('Freizeitangebot erstellen');
            });
    }

    /**
     * @param string $pageName
     * @return iterable
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }

    /**
     * If the user make changes on a entity entry
     * so wee set the new state of Entry
     *
     * @param EntityManagerInterface $entityManager
     * @param $entityInstance
     */
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (method_exists($entityInstance, 'setIsUserMadeChanges')) {
            $entityInstance->setIsUserMadeChanges(true);
        }

        parent::updateEntity($entityManager, $entityInstance);
    }


    /**
     * @param string $entityFqcn
     * @return Leisure|mixed
     */
    public function createEntity(string $entityFqcn)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $leisure = new Leisure();
        $leisure->setUserId((int)$user->getId());
        $leisure->setIsUserMadeChanges(true);

        return $leisure;
    }
}
