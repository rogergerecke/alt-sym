<?php

namespace App\Controller\Admin;

use App\Entity\Hostel;
use App\Entity\RoomAmenities;
use App\Entity\User;
use App\Repository\AmenitiesTypesRepository;
use App\Repository\CountrysRepository;
use App\Repository\CurrencyRepository;
use App\Repository\FederalStateRepository;
use App\Repository\RoomAmenitiesRepository;
use App\Repository\UserRepository;
use App\Service\AdminMessagesHandler;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use FM\ElfinderBundle\Form\Type\ElFinderType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Security\Core\Security;

/**
 * Class HostelCrudController
 * @package App\Controller\Admin
 */
class HostelCrudController extends AbstractCrudController
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var RoomAmenities
     */
    private $roomAmenities;
    /**
     * @var CountrysRepository
     */
    private $countrysRepository;
    /**
     * @var CurrencyRepository
     */
    private $currencyRepository;
    /**
     * @var FederalStateRepository
     */
    private $federalStateRepository;
    /**
     * @var AmenitiesTypesRepository
     */
    private $amenitiesTypesRepository;
    /**
     * @var Security
     */
    private $security;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var
     */
    private $user_id;
    /**
     * @var AdminMessagesHandler
     */
    private $adminMessagesHandler;

    /**
     * HostelCrudController constructor.
     * @param UserRepository $userRepository
     * @param RoomAmenitiesRepository $roomAmenities
     * @param CountrysRepository $countrysRepository
     * @param CurrencyRepository $currencyRepository
     * @param FederalStateRepository $federalStateRepository
     * @param AmenitiesTypesRepository $amenitiesTypesRepository
     * @param Security $security
     * @param EntityManagerInterface $em
     * @param AdminMessagesHandler $adminMessagesHandler
     */
    public function __construct(
        UserRepository $userRepository,
        RoomAmenitiesRepository $roomAmenities,
        CountrysRepository $countrysRepository,
        CurrencyRepository $currencyRepository,
        FederalStateRepository $federalStateRepository,
        AmenitiesTypesRepository $amenitiesTypesRepository,
        Security $security,
        EntityManagerInterface $em,
        AdminMessagesHandler $adminMessagesHandler
    ) {
        $this->userRepository = $userRepository;
        $this->roomAmenities = $roomAmenities;
        $this->countrysRepository = $countrysRepository;
        $this->currencyRepository = $currencyRepository;
        $this->federalStateRepository = $federalStateRepository;
        $this->amenitiesTypesRepository = $amenitiesTypesRepository;
        $this->security = $security;
        $this->em = $em;
        $this->adminMessagesHandler = $adminMessagesHandler;


        // get the user id from the logged in user
        if (null !== $this->security->getUser()) {
            $this->user_id = $this->security->getUser()->getId();
        }
    }

    /**
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return Hostel::class;
    }


    /**
     * Modified index builder to show only
     * hostels for the logged in user on
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
        $qb->andWhere($alias[0].'.user_id = '.$this->user_id);

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
            ->setPageTitle(Crud::PAGE_INDEX, 'Ihre Unterkünfte')
            ->setPageTitle(Crud::PAGE_NEW, 'Unterkunft anlegen')
            ->setHelp(
                Crud::PAGE_NEW,
                'Hier können Sie Ihre Unterkunft anlegen.  Die Felder mit rotem Punkt sind Pflichtfelder.'
            );
    }


    /**
     * @param string $pageName
     * @return iterable
     */
    public function configureFields(string $pageName): iterable
    {

        // id fields
        $id = IdField::new('id');
        $user_id = IntegerField::new('user_id')
            ->setFormTypeOption('disabled', true);

        // data fields
        $hostel_name = TextField::new('hostel_name');

        $image = TextField::new('image', 'Erstes Bild')
            ->setFormType(ElFinderType::class)
            ->setFormTypeOptions(['instance' => 'banner', 'enable' => true]);

        $address = TextField::new('address', 'Straße');
        $address_sub = TextField::new('address_sub', 'Adress zusatz');
        $postcode = IntegerField::new('postcode', 'PLZ');
        $city = TextField::new('city', 'Stadt');

        /* Build the federal state dropdown field SH, NRW */
        $state = TextField::new('state', 'Bundesland')->setHelp(
            'In welchem Bundesland liegt Ihre Unterkunft'
        )
            ->setFormType(ChoiceType::class)
            ->setFormTypeOptions(
                [
                    'choices'  => [
                        $this->buildFederalStateOptions(),
                    ],
                    'group_by' => 'id',
                ]
            );

        $country = TextField::new('country', 'Land');// todo add dropdown
        $country_id = IntegerField::new('country_id'); // intern filed only

        $longitude = NumberField::new('longitude');
        $latitude = NumberField::new('latitude');
        $phone = TextField::new('phone', 'Festnezt');
        $mobile = TextField::new('mobile');
        $fax = TextField::new('fax');
        $web = UrlField::new('web');
        $email = EmailField::new('email');

        /* Build the currency dropdown field EUR, GBP */
        $currency = TextField::new('currency', 'Währung')->setHelp(
            'Die Währung in der Sie abrechnen'
        )
            ->setFormType(ChoiceType::class)
            ->setFormTypeOptions(
                [
                    'choices'  => [
                        $this->buildCurrencyOptions(),
                    ],
                    'group_by' => 'id',
                ]
            );

        /* amenities choices array NO_SMOKING, W-LAN*/
        $amenities = CollectionField::new('amenities', 'Ausstattung')->setHelp(
            'Ausstattung die generell im Hostel verfügbar ist'
        )
            ->setEntryType(ChoiceType::class)
            ->setFormTypeOptions(
                [
                    'entry_options' => [
                        'choices'  => [
                            $this->buildRoomAmenitiesOptions(),
                        ],
                        'label'    => false,
                        'group_by' => 'id',
                    ],
                ]
            );

        $description = TextEditorField::new('description', 'Beschreibung');

        // api connection parameter for hostel availability import autogenerated
        $api_key = TextField::new('api_key')->setHelp(
            'Ihr API schlüssel für den Import'
        )->setFormTypeOption('disabled', true);

        $hostel_availability_url = UrlField::new('hostel_availability_url')->setHelp(
            'URL für den Import der Zimmerverfügbarkeit. Abruf 1x Stündlich.'
        );

        // Extra cost field only by admin editable
        $sort = IntegerField::new('sort');
        $startpage = BooleanField::new('startpage');
        $toplisting = BooleanField::new('toplisting');
        $top_placement_finished = DateTimeField::new('top_placement_finished');

        // Hostel on or offline switch
        $status = BooleanField::new('status');

        $hostel_type = TextField::new('hostel_type', 'Unterkunft-Typ')
            ->setFormType(ChoiceType::class)
            ->setFormTypeOptions(
                [
                    'choices'  => [
                        $this->buildAmenitiesTypesOptions(),
                    ],
                    'group_by' => 'id',
                ]
            );


        // output fields by page
        if (Crud::PAGE_INDEX === $pageName) {
            return [$user_id, $hostel_name, $address, $postcode, $city, $status];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [
                $id,
                $user_id,
                $hostel_name,
                $hostel_type,
                $image,
                $address,
                $address_sub,
                $postcode,
                $city,
                $state,
                $country,
                $country_id,
                $longitude,
                $latitude,
                $phone,
                $mobile,
                $fax,
                $web,
                $email,
                $currency,
                $amenities,
                $description,
                $hostel_availability_url,
                $sort,
                $startpage,
                $toplisting,
                $top_placement_finished,
                $status,
            ];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [
                $user_id,
                $hostel_type,
                $hostel_name,
                $image,
                $address,
                $address_sub,
                $postcode,
                $city,
                $state,
                $country,
                $country_id,
                $longitude,
                $latitude,
                $phone,
                $mobile,
                $fax,
                $web,
                $email,
                $currency,
                $amenities,
                $description,
                $api_key,
                $hostel_availability_url,
                $status,
            ];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [
                $user_id,
                $hostel_name,
                $hostel_type,
                $image,
                $address,
                $address_sub,
                $postcode,
                $city,
                $state,
                $country,
                $country_id,
                $longitude,
                $latitude,
                $phone,
                $mobile,
                $fax,
                $web,
                $email,
                $currency,
                $amenities,
                $description,
                $api_key,
                $hostel_availability_url,
                $status,
            ];
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

            // add message to admin log
            $this->adminMessagesHandler->addInfo(
                "Der Benutzer-ID: $this->user_id hat an einer Unterkunft einstellung geändert.",
                "Ein Benutzer hat eine Unterkunft bearbeitet."
            );
        }

        parent::updateEntity($entityManager, $entityInstance);
    }

    /**
     * Create a new hostel with the id from
     * the logged in user a user cant have
     * many hostel's
     *
     * @param string $entityFqcn
     * @return Hostel|mixed
     */
    public function createEntity(string $entityFqcn)
    {

        $user = $this->userRepository->findOneBy(['email' => $this->getUser()->getUsername()]);

        if (null !== $user) {
            $hostel = new Hostel();
            $hostel->setUserId($user->getId());
            $hostel->setIsUserMadeChanges(true);

            // add message to admin log
            $this->adminMessagesHandler->addInfo(
                "Der Benutzer-ID: ".$user->getName()." hat eine neue Unterkunft angelegt.",
                "Ein Benutzer hat eine neue Unterkunft angelegt."
            );

            return $hostel;
        }
    }

    ##########################################################
    #
    #
    #   Protected Helper Function
    #
    #
    ##########################################################

    /**
     * Return a array with the select options
     * for the federal state selection field
     *
     * @return array
     */
    protected function buildFederalStateOptions()
    {
        $options = [];

        $federalStates = $this->federalStateRepository->findBy(['status' => true], ['sort' => 'ASC']);

        foreach ($federalStates as $federalState) {
            $options[$federalState->getName()] = $federalState->getCode();
        }

        return $options;
    }

    /**
     * Return a array with the select options
     * for the currency selection field
     *
     * @return array
     */
    protected function buildCurrencyOptions()
    {
        $options = [];

        $currencies = $this->currencyRepository->findBy(['status' => true], ['sort' => 'ASC']);

        foreach ($currencies as $currency) {
            $options[$currency->getName()] = $currency->getCode();
        }

        return $options;
    }

    /**
     * Return a array with the select options
     * for the room amenities selection field
     *
     * @return array
     */
    protected function buildRoomAmenitiesOptions()
    {

        $options = [];

        // get from db
        $roomAmenities = $this->roomAmenities->getRoomAmenitiesWithDescription();

        // build option array
        foreach ($roomAmenities as $roomAmenity) {
            $options[$roomAmenity[0]->getName()] = $roomAmenity['name'];
        }

        return $options;
    }

    /**
     * Return a array with the select options
     * for the amenities types selection field
     *
     * @return array
     */
    protected function buildAmenitiesTypesOptions()
    {
        $options = [];

        $amenities_types = $this->amenitiesTypesRepository->findBy(['status' => true], ['sort' => 'ASC']);

        foreach ($amenities_types as $types) {
            $options[$types->getName()] = $types->getName();
        }

        return $options;
    }

    /**
     * @return mixed
     */
    /*protected function getHostels()
    {

        $user = $this->em
            ->getRepository(User::class)
            ->find($this->user_id);

        $hostels = $user->getHostels();

        return $hostels[0]->getPostcode();
    }*/
}
