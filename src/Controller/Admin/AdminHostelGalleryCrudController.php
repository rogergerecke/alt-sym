<?php

namespace App\Controller\Admin;

use App\Entity\HostelGallery;
use App\Repository\HostelRepository;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Filter\FilterConfiguratorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FilterConfigDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FilterDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use FM\ElfinderBundle\Form\Type\ElFinderType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class AdminHostelGalleryCrudController extends AbstractCrudController
{
    private $hostels;
    /**
     * @var FilterConfiguratorInterface
     */
    private $filter;
    /**
     * @var UserInterface|null
     */
    private $user;
    /**
     * @var HostelRepository
     */
    private $hostelRepository;

    /**
     * AdminHostelGalleryCrudController constructor.
     * @param HostelRepository $hostelRepository
     */
    public function __construct(HostelRepository $hostelRepository)
    {

        $this->hostelRepository = $hostelRepository;
    }

    public static function getEntityFqcn(): string
    {
        return HostelGallery::class;
    }

    /**
     * Modified index builder to show only
     * images for the logged in user on
     * index table
     *
     * @param SearchDto $searchDto
     * @param EntityDto $entityDto
     * @param FieldCollection $fields
     * @param FilterCollection $filters
     * @return QueryBuilder
     */
  /*  public function createIndexQueryBuilder(
        SearchDto $searchDto,
        EntityDto $entityDto,
        FieldCollection $fields,
        FilterCollection $filters
    ): QueryBuilder {

        $qb = $this->get(EntityRepository::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $alias = $qb->getRootAliases();

        foreach ($this->hostels as $hostel) {
            $qb->andWhere($alias[0].'.hostel_id = '.$hostel->getId());
        }

        return $qb;
    }*/

    public function configureCrud(Crud $crud): Crud
    {

        return $crud
            ->addFormTheme('@FMElfinderBundle/Form/elfinder_widget.html.twig')
            ->setPageTitle(Crud::PAGE_INDEX, 'Bilder Übersicht')
            ->setPageTitle(Crud::PAGE_NEW, 'Bild zur Unterkunft hinzufügen');
    }


    public function configureFields(string $pageName): iterable
    {

        $id = IdField::new('id');
        $hostel_id = IntegerField::new('hostel_id', 'Bild von Unterkunft')
            ->setFormType(ChoiceType::class)
            ->setFormTypeOptions(
                [

                    'choices' => [
                        $this->buildHostelChoices(),
                    ],

                    'group_by' => 'id',

                ]
            );
        $name = TextField::new('name', 'Text für Suchmaschiene');
        $image = ImageField::new('image', 'Bild')
            ->setFormType(ElFinderType::class)
            ->setFormTypeOptions(['instance' => 'hostel', 'enable' => true])
            ->setHelp('Vorzugsweise 1000x600 Pixel in guter Qualität');
        $status = BooleanField::new('status');
        $sort = IntegerField::new('sort', 'Sortierung');

        switch ($pageName) {
            case Crud::PAGE_INDEX:
            case Crud::PAGE_DETAIL:
            case Crud::PAGE_NEW:
            case Crud::PAGE_EDIT:
                return [
                    $hostel_id,
                    $name,
                    $image,
                    $status,
                    $sort
                ];
                break;
        }
    }

    ##########################################################
    #
    #
    #   Entity Override
    #
    #
    ##########################################################


    /**
     * Set the default status of uploade image
     *
     * to true
     *
     * @param string $entityFqcn
     * @return HostelGallery|mixed
     */
    public function createEntity(string $entityFqcn)
    {
        $gallery = new HostelGallery();
        $gallery->setStatus(true);

        return $gallery;
    }


    ##########################################################
    #
    #
    #   Protected Helper Function
    #
    #
    ##########################################################

    protected function buildHostelChoices()
    {
        if (null !== $this->hostels = $this->hostelRepository->findAll()) {
            foreach ($this->hostels as $hostel) {
                $hostel_names[$hostel->getHostelName()] = $hostel->getId();
            }
        }

        return $hostel_names;
    }
}
