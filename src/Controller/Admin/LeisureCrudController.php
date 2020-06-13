<?php

namespace App\Controller\Admin;

use App\Entity\Leisure;
use Doctrine\ORM\EntityManagerInterface;
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
