<?php

namespace App\Controller\User;

use App\Entity\OccupancyPlan;
use App\Repository\HostelRepository;
use App\Repository\OccupancyPlanRepository;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class OccupancyPlanCrudController
 * @package App\Controller\Admin
 */
class OccupancyPlanCrudController extends AbstractCrudController
{
    private $hostels;
    private $user_id;
    /**
     * @var UserInterface|null
     */
    private $user;
    /**
     * @var Security
     */
    private $security;
    /**
     * @var OccupancyPlanRepository
     */
    private $occupancyPlanRepository;

    public function __construct(Security $security, OccupancyPlanRepository $occupancyPlanRepository)
    {
        $this->security = $security;
        $this->occupancyPlanRepository = $occupancyPlanRepository;

        if (null !== $security->getUser()) {
            $this->user = $security->getUser();
            $this->user_id = $this->user->getId();
            $this->hostels = $this->user->getHostels();
        }
    }

    public static function getEntityFqcn(): string
    {
        return OccupancyPlan::class;
    }

    /**
     * Override the edit entity function
     * to prevent entity id hack by false user
     *
     * @param AdminContext $context
     * @return KeyValueStore|RedirectResponse|Response
     */
    public function edit(AdminContext $context)
    {
        // get all ids for the user
        $ids = null;
        foreach ($this->hostels as $hostel) {
            if ($hoc = $this->occupancyPlanRepository->findBy(['hostel_id' => $hostel->getId()])) {
                foreach ($hoc as $h) {
                    $ids[] = $h->getId();
                }
            }
        }

        // no ids for user
        if (!$ids) {
            $this->addFlash('warning', 'Sie haben noch keine Belegungspläne angelegt');
            $this->redirectToRoute('user');
        }

        // permission denied url entity hack
        if (!in_array($context->getEntity()->getPrimaryKeyValue(), $ids)) {
            $this->addFlash('warning', 'Sie dürfen keine Fremden Belegungspläne bearbeiten');
            $this->redirectToRoute('user');
        } else {
            return parent::edit($context);
        }

        // return empty object
        return $this->render(
            'bundles/EasyAdmin/crazy_horse.html.twig',
            [

            ]
        );
    }

    /**
     * Override the delete entity function
     * to prevent entity id hack by false user
     *
     * @param AdminContext $context
     * @return KeyValueStore|RedirectResponse|Response
     */
    public function delete(AdminContext $context)
    {
        // get all ids for the user
        $ids = null;
        foreach ($this->hostels as $hostel) {
            if ($hoc = $this->occupancyPlanRepository->findBy(['hostel_id' => $hostel->getId()])) {
                foreach ($hoc as $h) {
                    $ids[] = $h->getId();
                }
            }
        }

        // no ids for user
        if (!$ids) {
            $this->addFlash(
                'warning',
                'Sie haben noch keine Belegungspläne angelegt, darum können Sie auch keine Löschen'
            );
            $this->redirectToRoute('user');
        }

        // permission denied url entity hack
        if (!in_array($context->getEntity()->getPrimaryKeyValue(), $ids)) {
            $this->addFlash('warning', 'Sie dürfen keine Fremden Belegungspläne löschen');
            $this->redirectToRoute('user');
        } else {
            return parent::delete($context);
        }

        // return empty object
        return $this->render(
            'bundles/EasyAdmin/crazy_horse.html.twig',
            [

            ]
        );
    }

    /**
     * Modified index builder to show only
     * occupancy for the logged in user on
     * index table
     *
     * @param SearchDto $searchDto
     * @param EntityDto $entityDto
     * @param FieldCollection $fields
     * @param FilterCollection $filters
     * @return QueryBuilder
     */
    public function createIndexQueryBuilder(
        SearchDto $searchDto,
        EntityDto $entityDto,
        FieldCollection $fields,
        FilterCollection $filters
    ): QueryBuilder {

        $qb = $this->get(EntityRepository::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $alias = $qb->getRootAliases();
        foreach ($this->hostels as $hostel) {
            $qb->orWhere($alias[0].'.hostel_id = '.$hostel->getId());
        }

        return $qb;
    }

    public function configureFields(string $pageName): iterable
    {

        $id = IdField::new('id');

        // build the hostel selection filed load hostels from user
        $hostel_id = AssociationField::new('hostel', 'Unterkunft')
            ->setFormTypeOptions(
                [
                    'query_builder' => function (HostelRepository $hr) {
                        return $hr->createQueryBuilder('h')
                            ->andWhere("h.user_id = $this->user_id");
                    },
                ]
            )
            ->setHelp('Hostel auswählen zu dem ein Belegungsplan angelegt werden soll');

        $date_from = DateField::new('date_from', 'Von');
        $date_to = DateField::new('date_to', 'Bis');

        // create the utilization value options
        $utilization = IntegerField::new('utilization', 'Auslastung')
            ->setFormType(ChoiceType::class)
            ->setFormTypeOptions(
                [
                    'choices'  => [
                        'Frei' => '1',
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
