<?php

namespace App\Controller\Admin;

use App\Entity\OccupancyPlan;
use App\Repository\HostelRepository;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class AdminOccupancyPlanCrudController
 * @package App\Controller\Admin
 */
class AdminOccupancyPlanCrudController extends AbstractCrudController
{

    /**
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return OccupancyPlan::class;
    }

    /**
     * @param Crud $crud
     * @return Crud
     */
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Belegungsplan')
            ->setPageTitle(Crud::PAGE_NEW, 'Belegungsplan');
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
                return $action->setIcon('fa fa-file-alt')->setLabel('Belegungsplan erstellen');
            });
    }


    /**
     * @param string $pageName
     * @return iterable
     */
    public function configureFields(string $pageName): iterable
    {

        $id = IdField::new('id');

        // build the hostel selection filed load hostels from user
        $hostel_id = AssociationField::new('hostel', 'Unterkunft')
            ->setHelp('Bitte wählen Sie die Unterkunft aus zu der ein Belegungsplan angelegt werden soll');

        $date_from = DateField::new('date_from', 'Von');
        $date_to = DateField::new('date_to', 'Bis');

        // create the utilization value options
        $utilization = IntegerField::new('utilization', 'Auslastung')
            ->setFormType(ChoiceType::class)
            ->setFormTypeOptions(
                [
                    'choices'  => [
                        'Frei'             => '1',
                        'Halb ausgelastet' => '2',
                        'Voll ausgelastet' => '3',
                    ],
                    'group_by' => 'id',
                ]
            );

        switch ($pageName) {
            case Crud::PAGE_DETAIL:
            case Crud::PAGE_INDEX:
                return [
                    $id,
                    $hostel_id,
                    $date_from,
                    $date_to,
                    $utilization,
                ];

            case Crud::PAGE_EDIT:
            case Crud::PAGE_NEW:
                return [
                    $hostel_id,
                    $date_from,
                    $date_to,
                    $utilization,
                ];
        }
    }
}
