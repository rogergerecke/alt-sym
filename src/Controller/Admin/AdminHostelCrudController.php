<?php

namespace App\Controller\Admin;

use App\Entity\Hostel;
use App\Entity\RoomAmenities;
use App\Repository\AmenitiesTypesRepository;
use App\Repository\CurrencyRepository;
use App\Repository\FederalStateRepository;
use App\Repository\RoomAmenitiesRepository;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Configurator\SelectConfigurator;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use FM\ElfinderBundle\Form\Type\ElFinderType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AdminHostelCrudController extends AbstractCrudController
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

    public function __construct(
        UserRepository $userRepository,
        RoomAmenitiesRepository $roomAmenities,
        CurrencyRepository $currencyRepository,
        FederalStateRepository $federalStateRepository,
        AmenitiesTypesRepository $amenitiesTypesRepository
    ) {
        $this->userRepository = $userRepository;
        $this->roomAmenities = $roomAmenities;
        $this->currencyRepository = $currencyRepository;
        $this->federalStateRepository = $federalStateRepository;
        $this->amenitiesTypesRepository = $amenitiesTypesRepository;
    }

    public static function getEntityFqcn(): string
    {
        return Hostel::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('edit', 'Admin: Unterkünfte')
            ->setPageTitle('index', 'Admin: Unterkünfte')
            ->setHelp('index', 'Hier ist die Übersicht der eingetragenen Unterkünfte.')
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig')
            ->addFormTheme('@FMElfinderBundle/Form/elfinder_widget.html.twig')
            ->setDefaultSort(['status' => 'DESC']);
    }


    public function configureFields(string $pageName): iterable
    {

        // id fields
        /* $id = IdField::new('id');*/
        $user_id = IntegerField::new('user_id')
            ->setFormType(ChoiceType::class)
            ->setFormTypeOptions(
                [
                    'choices'  => [
                        $this->buildUserOptions(),
                    ],
                    'group_by' => 'id',
                ]
            );

        // data fields
        $hostel_name = TextField::new('hostel_name', 'Name');


        $preview_image = ImageField::new('image');

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

        $room_types = TextField::new('room_types');// todo dropdown array[]

        /* amenities choices array */
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
            );//todo json*/

        $description = TextareaField::new('description', 'Beschreibung')
            ->setFormType(CKEditorType::class);


        // api connection parameter for hostel availability import autogenerated
        $api_key = TextField::new('api_key')->setHelp(
            'Ihr API schlüssel für den Import'
        )->setFormTypeOption('disabled', true);

        $hostel_availability_url = UrlField::new('hostel_availability_url')->setHelp(
            'URL für den Import der Zimmerverfügbarkeit. Abruf 1x Stündlich.'
        );

        // Extra cost field only by admin editable
        $panel_marketing = FormField::addPanel('Marketing', 'fa fa-bullhorn')->setHelp(
            'Marketing Einstellung die,  die Rechte des Gekauften Paketes 
            überschreiben sie sind den privilegien Einstellung im Benutzerkonto 
            übergeordnet und sollten nur in Ausnahmefällen genutzt werden.'
        );
        $sort = IntegerField::new('sort', 'Sortierung');
        $startpage = BooleanField::new('startpage', 'Auf Startseite einblenden');
        $toplisting = BooleanField::new('toplisting', 'Top-Platzierung außerhalb der Pakete');
        $top_placement_finished = DateTimeField::new('top_placement_finished', 'Ende der Top-Platzierung');

        // Hostel on or offline switch
        $status = BooleanField::new('status');
        $distance_to_see = NumberField::new('distance_to_see', 'Entfernung zum See')->setNumDecimals(2);

        $image = TextField::new('image', 'Erstes Bild')
            ->setFormType(ElFinderType::class)
            ->setFormTypeOptions(
                ['instance' => 'banner', 'enable' => true]
            )->setHelp(
                'Größe 255x255px das Bild kann man zuschneiden und als Kopie Speichern'
            )// banner instance is configured in fm_elfinder.yaml
        ;

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

        $stars = IntegerField::new('stars', 'Hotel Sterne')->setHelp(
            'Nur angeben wenn Sie wirklich über Sterne nach DEHOGA verfügen.'
        );

        switch ($pageName) {
            case Crud::PAGE_INDEX:
            case Crud::PAGE_DETAIL:
                return [
                    $user_id,
                    $hostel_name,
                    $preview_image,
                    $address,
                    $postcode,
                    $city,
                    $status,
                ];
                break;
            case Crud::PAGE_NEW:
            case Crud::PAGE_EDIT:
                return [
                    $user_id,
                    $hostel_name,
                    $stars,
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
                    $distance_to_see,
                    $panel_marketing,
                    $sort,
                    $startpage,
                    $toplisting,
                    $top_placement_finished,
                    $status,
                ];
                break;
        }
    }


    ##########################################################
    #
    #
    #   Protected Helper Function
    #
    #
    ##########################################################

    protected function buildUserOptions()
    {
        $users = $this->userRepository->findAll();

        foreach ($users as $user) {
            $label = "ID:".$user->getId()." ".$user->getName();
            $options[$label] = $user->getId();
        }

        return $options;
    }

    /**
     * Create the option array
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
     * Create the option array
     * @return array
     */
    protected function buildCurrencyOptions()
    {
        $options = [];

        $currencys = $this->currencyRepository->findBy(['status' => true], ['sort' => 'ASC']);

        foreach ($currencys as $currency) {
            $options[$currency->getName()] = $currency->getCode();
        }

        return $options;
    }

    /**
     * Create the option array
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
     * Create the option array
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
}
