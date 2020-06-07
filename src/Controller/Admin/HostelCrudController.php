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
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
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
use FM\ElfinderBundle\Form\Type\ElFinderType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Security\Core\Security;

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
    private $user_id;


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
     */
    public function __construct(
        UserRepository $userRepository,
        RoomAmenitiesRepository $roomAmenities,
        CountrysRepository $countrysRepository,
        CurrencyRepository $currencyRepository,
        FederalStateRepository $federalStateRepository,
        AmenitiesTypesRepository $amenitiesTypesRepository,
        Security $security,
        EntityManagerInterface $em
    ) {
        $this->userRepository = $userRepository;
        $this->roomAmenities = $roomAmenities;
        $this->countrysRepository = $countrysRepository;
        $this->currencyRepository = $currencyRepository;
        $this->federalStateRepository = $federalStateRepository;
        $this->amenitiesTypesRepository = $amenitiesTypesRepository;

        $this->security = $security;

        $this->em = $em;
        if (null !== $this->security->getUser()) {
            $this->user_id = $this->security->getUser()->getId();
        }
    }

    public static function getEntityFqcn(): string
    {
        return Hostel::class;
    }

    /**
     * Create a new hostel with
     * the id from the logged in user
     * a user cant have many hostel's
     *
     * @param string $entityFqcn
     * @return Hostel|mixed
     */
    public function createEntity(string $entityFqcn)
    {
        /* $user = $this->get('security.token_storage')->getToken()->getUser();*/

        $user = $this->userRepository->findOneBy(['email' => $this->getUser()->getUsername()]);

        if (null !== $user) {
            $hostel = new Hostel();
            $hostel->setUserId($user->getId());

            return $hostel;
        }

    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->addFormTheme('@FMElfinderBundle/Form/elfinder_widget.html.twig')
            ->setPageTitle(Crud::PAGE_NEW, 'Unterkunft anlegen')
            ->setHelp(
                Crud::PAGE_NEW,
                'Hier können Sie Ihre Unterkunft anlegen.  Die Felder mit rotem Punkt sind Pflichtfelder.'
            );

    }


    public function configureFields(string $pageName): iterable
    {

        // id fields
        $id = IdField::new('id');
        $user_id = IntegerField::new('user_id')->setFormType(HiddenType::class);

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

        $room_types = TextField::new('room_types');// todo dropdown array[]

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

        // api connection parameter for hostel availability import
        $api_key = TextField::new('api_key')->setHelp(
            'Ihr API schlüssel für den Import'
        );// todo only display autogenarate UUid
        $hostel_availability_url = UrlField::new('hostel_availability_url')->setHelp(
            'URL für den Import der Zimmerverfügbarkeit. Abruf 1x Stündlich.'
        );

        // Extra cost field only by admin editable
        $sort = TextField::new('sort'); //todo add only by admin over extra pay
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
                $room_types,
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
                $room_types,
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
                $room_types,
                $amenities,
                $description,
                $api_key,
                $hostel_availability_url,
                $status,
            ];
        }
    }




    ##########################################
    #
    # Helper function protected
    #
    ##########################################

    protected function getHostels(){

        $user = $this->em
            ->getRepository(User::class)
            ->find($this->user_id);

        $hostels = $user->getHostels();

        return $hostels[0]->getPostcode();
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

}
