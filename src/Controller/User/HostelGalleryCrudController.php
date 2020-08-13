<?php

namespace App\Controller\User;

use App\Entity\HostelGallery;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use FM\ElfinderBundle\Form\Type\ElFinderType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class HostelGalleryCrudController
 * @package App\Controller\Admin
 */
class HostelGalleryCrudController extends AbstractCrudController
{
    /**
     * @var
     */
    private $hostels;

    /**
     * @var UserInterface|null
     */
    private $user;

    /**
     * HostelGalleryCrudController constructor.
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        if (null !== $security->getUser()) {
            $this->user = $security->getUser();
            $this->hostels = $this->user->getHostels();
        }
    }

    /**
     * @return string
     */
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

    /**
     * @param Crud $crud
     * @return Crud
     */
    public function configureCrud(Crud $crud): Crud
    {

        return $crud
            ->addFormTheme('@FMElfinderBundle/Form/elfinder_widget.html.twig')
            ->setPageTitle(Crud::PAGE_INDEX, 'Bilder Übersicht')
            ->setPageTitle(Crud::PAGE_NEW, 'Bild zur Unterkunft hinzufügen');
    }


    /**
     * @param string $pageName
     * @return iterable
     */
    public function configureFields(string $pageName): iterable
    {


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

        $name = TextField::new('name', 'Text für Suchmaschine');
        $image = ImageField::new('image', 'Bild')
            ->setFormType(ElFinderType::class)
            ->setFormTypeOptions(['instance' => 'hostel', 'enable' => true])
            ->setHelp('Vorzugsweise 1000x600 Pixel in guter Qualität');
        $status = BooleanField::new('status');

        switch ($pageName) {
            case Crud::PAGE_INDEX:
            case Crud::PAGE_DETAIL:
                return [
                    $image,
                    $name,
                    $status,
                ];
            case Crud::PAGE_NEW:
            case Crud::PAGE_EDIT:
                return [
                    $hostel_id,
                    $name,
                    $image,
                    $status,
                ];
        }
    }

    /**
     * @return mixed
     */
    protected function buildHostelChoices()
    {
        $hostel_names = null;

        foreach ($this->hostels as $hostel) {
            $hostel_names[$hostel->getHostelName()] = $hostel->getId();
        }

        return $hostel_names;
    }

    #############################
    #
    # Helper function protected
    #
    #############################

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
}
