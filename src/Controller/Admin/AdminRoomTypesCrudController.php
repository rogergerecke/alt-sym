<?php

//class AdminRoomTypesCrudController extends AbstractCrudController
//{
//    /**
//     * @var HostelRepository
//     */
//    private $hostelRepository;
//    /**
//     * @var RoomAmenitiesRepository
//     */
//    private $roomAmenitiesRepository;
//    /**
//     * @var Security
//     */
//    private $security;
//    private $user_id;
//
//    /**
//     * RoomTypesCrudController constructor.
//     * @param HostelRepository $hostelRepository
//     * @param RoomAmenitiesRepository $roomAmenitiesRepository
//     * @param Security $security
//     */
//    public function __construct(
//        HostelRepository $hostelRepository,
//        RoomAmenitiesRepository $roomAmenitiesRepository,
//        Security $security
//    ) {
//        $this->hostelRepository = $hostelRepository;
//        $this->roomAmenitiesRepository = $roomAmenitiesRepository;
//
//        $this->security = $security;
//
//        // get the user id from the logged in user
//        if (null !== $this->security->getUser()) {
//            $this->user_id = $this->security->getUser()->getId();
//        }
//    }
//
//    public function configureCrud(Crud $crud): Crud
//    {
//        return $crud
//            ->setPageTitle(Crud::PAGE_NEW, 'Zimmer Angebot anlegen')
//            ->setHelp(Crud::PAGE_NEW, 'Hier können Sie Zimmer mit verschiedenen Preisen und eigenschaften anlegen.');
//    }
//
//
//    /**
//     * @return string
//     */
//    public static function getEntityFqcn(): string
//    {
//        return RoomTypes::class;
//    }
//
//    /**
//     * @param string $pageName
//     * @return iterable
//     */
//    public function configureFields(string $pageName): iterable
//    {
//
//        $hostel_id = AssociationField::new('hostel')
//            ->setCrudController(AdminHostelCrudController::class);
//
//            // Additional booking fee
//        $booking_fee = MoneyField::new('booking_fee', 'Zusätzliche Buchungsgebühren')
//            ->setCurrency('EUR')
//            ->setHelp(
//                'Zusätzliche Buchungsgebühren, die die Nutzer dem Werbetreibenden zahlen müssen.
//                Bitte betrachten Sie diese Gebühr als durchschnittliche Gebühr pro Tag für den gesamten Aufenthalt.'
//            );
//
//        // If the breakfast include or not
//        $breakfast_included = BooleanField::new('breakfast_included', 'Frühstück inclusive');
//
//        // Simply the currency type
//        $currency = TextField::new('currency', 'Währung')->setFormType(ChoiceType::class)
//            ->setFormTypeOptions(
//                [
//                    'choices'  => [
//                        'Euro'   => 'EUR',
//                        'Pfund'  => 'GBP',
//                        'Dollar' => 'USD',
//                    ],
//                    'group_by' => 'id',
//                ]
//            );
//
//        // todo discount
//        // Discount array field for additional discounts for this room
//        $discounts = ArrayField::new('discounts', 'Rabat Array')
//            ->setHelp('Liste der auf den Preis anwendbaren Rabatte');
//
//        // The final price of the room inclusive tax
//        $final_rate = MoneyField::new('final_rate', 'Endpreis')
//            ->setCurrency('EUR')
//            ->setHelp(
//                'Der endgültige Preis, den der Benutzer zahlen muss (Rabatte ausgeschlossen).
//                Bitte betrachten Sie diesen Preis als Durchschnittspreis pro Tag für den gesamten Aufenthalt.'
//            );
//
//        // Is the cancellation of the room order for free
//        $free_cancellation = BooleanField::new('free_cancellation', 'Gratis Stornierung');
//
//        // Additional hotel fee
//        $hotel_fee = MoneyField::new('hotel_fee', 'Hotelgebühr')
//            ->setCurrency('EUR')
//            ->setHelp(
//                'Zusätzliche Gebühren, die die Benutzer zahlen müssen,
//                um das Hotel zu bezahlen. Bitte betrachten Sie diese Gebühr
//                als durchschnittliche Gebühr pro Tag für den gesamten Aufenthalt.'
//            );
//
//        // The rate model view for showing exclusive stiling for the offer (Exclusive Offer Banner)
//        $rate_type = TextField::new('rate_type')
//            ->setHelp(
//                'Gibt an, ob die Rate exklusiv für Mobilgeräte oder
//                als Prämienrate gilt. Wenn nicht angegeben, wird die Rate als STANDARD betrachtet.'
//            )->setFormType(ChoiceType::class)
//            ->setFormTypeOptions(
//                [
//                    'choices'  => [
//                        'Standart'   => 'DEFAULT',
//                        'Honorieren' => 'REWARD',
//                        'Mobil'      => 'MOBILE',
//                    ],
//                    'group_by' => 'id',
//                ]
//            );
//
//        // Local tax for the region
//        $local_tax = NumberField::new('local_tax')
//            ->setHelp(
//                'Stadtsteuern. Bitte betrachten Sie diese Gebühr
//                als durchschnittliche Gebühr pro Tag für den gesamten Aufenthalt.'
//            );
//
//        // The meale code for the room price and type standard non all inclusive
//        $meal_code = TextField::new('meal_code', 'Verpflegungs Code')
//            ->setFormType(ChoiceType::class)
//            ->setFormTypeOptions(
//                [
//                    'choices'  => [
//                        [
//                            'Nur Zimmer'         => 'RO',
//                            'Bett und Frühstück' => 'BB',
//                            'Halbpension'        => 'HB',
//                            'Vollpension'        => 'FB',
//                            'Alles Inclusive'    => 'AI',
//                        ],
//                    ],
//                    'group_by' => 'id',
//                ]
//            );
//
//        // Have the room a individual landing page
//        $landing_page_url = UrlField::new('landing_page_url', 'Extra Landingpage Url')
//            ->setHelp('Gibt es eine extra Website nur für diese Zimmer');
//
//        // The netto price of the room exclusive vat and boni
//        $net_rate = MoneyField::new('net_rate', 'Netto Preis')
//            ->setCurrency('EUR')
//            ->setHelp(
//                'Nettotarif ohne Steuern. Bitte betrachten Sie diesen Preis
//                als Durchschnittspreis pro Tag für den gesamten Aufenthalt.'
//            );
//
//        $payment_type = TextField::new('payment_type', 'Zahlungsmethode');
//
//        // Have we a resort fee for this room
//        $resort_fee = MoneyField::new('resort_fee', 'Kurtaxe pro Tag')
//            ->setCurrency('EUR');
//
//
//        // The room amenities collection type build from db
//        $room_amenities = CollectionField::new('room_amenities', 'Zimmerausstattung')
//            ->setHelp('Weicht die Zimmer Ausstattung von der Globalen Unterkunft Ausstattung ab')
//            ->setEntryType(ChoiceType::class)
//            ->setFormTypeOptions(
//                [
//                    'entry_options' => [
//                        'choices'  => [
//                            $this->buildRoomAmenitiesOptions(),
//                        ],
//                        'label'    => false,
//                        'group_by' => 'id',
//                    ],
//                ]
//            );
//
//        $room_code = TextField::new('room_code', 'Zimmernummer / Stellplatznummer')
//            ->setHelp('Die Zimmernummer oder Stellplatznummer bei Camping');
//
//        $service_charge = MoneyField::new('service_charge', 'Servicegebühr')
//            ->setCurrency('EUR');
//
//        $url = UrlField::new('url', 'Website Url')
//            ->setHelp('Die Address zu Ihrer Website');
//
//        $vat = NumberField::new('vat', 'MwSt. Satz')
//            ->setFormType(NumberType::class)
//            ->setFormTypeOptions(
//                [
//                    'empty_data' => '19.00',
//                ]
//            );
//
//        $name = TextField::new('name', 'Angebots Name');
//        $is_handicapped_accessible = BooleanField::new('is_handicapped_accessible', 'Barrierefrei');
//
//        //Specific accommodation's category. E.g. "Einzelzimmer", "Stellplatz", "Junior Suite"
//        $accommodation_type = TextField::new('accommodation_type', 'Kategorie')
//            ->setFormType(ChoiceType::class)
//            ->setFormTypeOptions(
//                [
//                    'choices'  => [
//                        [
//                            'Einzelzimmer'   => 'Einzelzimmer',
//                            'Doppelzimmer'   => 'Doppelzimmer',
//                            'Mehrbettzimmer' => 'Mehrbettzimmer',
//                            'Penthouse'      => 'Penthouse',
//                            'Stellplatz'     => 'Stellplatz',
//                            'Holzhütte'      => 'Holzhütte',
//                            'Junior Suite'   => 'Junior Suite',
//                            'Suite'          => 'Suite',
//                        ],
//                    ],
//                    'group_by' => 'id',
//                ]
//            );
//
//        // Number of units the unique partner reference
//        $number_of_units = IntegerField::new('number_of_units', 'Anzahl dieses Angebotes')
//            ->setHelp('Wie oft verfügen Sie von dieser Art des Raumes');
//
//        // Numeric size of the unit in square feet or meters
//        $unit_size = IntegerField::new('unit_size', 'Raum / Platz größe');
//
//        // Square feet or square meters
//        $unit_type = TextField::new('unit_type', 'Größen Einheit')
//            ->setFormType(ChoiceType::class)
//            ->setFormTypeOptions(
//                [
//                    'choices'  => [
//                        [
//                            'Quadratmeter' => 'qm',
//                            'Square feet'  => 'ft²',
//                        ],
//                    ],
//                    'group_by' => 'id',
//                ]
//            );
//
//        // Number of guests allowed per unit
//        $unit_occupancy = IntegerField::new('unit_occupancy', 'Anzahl Gäste')
//            ->setHelp('Die erlaubte Gästeanzahl für diesen Raum, wichtig für die Suchfunktion');
//
//        $number_of_bedrooms = NumberField::new('number_of_bedrooms', 'Anzahl Schlafzimmer');
//        $number_of_bathrooms = NumberField::new('number_of_bathrooms', 'Anzahl Badezimmer');
//
//        // Number of floor where the unit is located
//        $floor_number = IntegerField::new('floor_number', 'Etage');
//
//        // House or apartment number
//        $unit_number = TextField::new('unit_number', 'Hausnummer / Apartment');
//
//        switch ($pageName) {
//            case Crud::PAGE_INDEX:
//                return [
//                    $hostel_id,
//                    $name,
//                    $currency,
//                    $vat,
//                    $discounts,
//                    $final_rate,
//                    $net_rate,
//                    $payment_type,
//                    $url,
//                    $landing_page_url,
//                    $meal_code,
//
//                ];
//                break;
//            case Crud::PAGE_DETAIL:
//                return [];
//                break;
//            case Crud::PAGE_EDIT:
//            case Crud::PAGE_NEW:
//                return [
//                    $name,
//                    $hostel_id,
//                    $booking_fee,
//                    $currency,
//                    $vat,
//                    $discounts,
//                    $final_rate,
//                    $hotel_fee,
//                    $rate_type,
//                    $local_tax,
//                    $net_rate,
//                    $payment_type,
//                    $resort_fee,
//                    $room_amenities,
//                    $room_code,
//                    $service_charge,
//                    $url,
//                    $landing_page_url,
//                    $meal_code,
//                    $accommodation_type,
//                    $breakfast_included,
//                    $free_cancellation,
//                    $is_handicapped_accessible,
//                    $number_of_units,
//                    $unit_size,
//                    $unit_type,
//                    $unit_occupancy,
//                    $number_of_bedrooms,
//                    $number_of_bathrooms,
//                    $floor_number,
//                    $unit_number,
//                ];
//                break;
//        }
//    }



namespace App\Controller\Admin;

use App\Entity\RoomTypes;
use App\Repository\HostelRepository;
use App\Repository\RoomAmenitiesRepository;
use App\Service\AdminMessagesHandler;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Security\Core\Security;

/**
 * Class RoomTypesCrudController
 * @package App\Controller\Admin
 */
class AdminRoomTypesCrudController extends AbstractCrudController
{
    /**
     * @var HostelRepository
     */
    private $hostelRepository;
    /**
     * @var RoomAmenitiesRepository
     */
    private $roomAmenitiesRepository;
    /**
     * @var Security
     */
    private $security;
    private $user_id;
    /**
     * @var AdminMessagesHandler
     */
    private $adminMessagesHandler;
    /**
     * @var bool
     */
    private $user;
    private $hostels;
    /**
     * @var CrudUrlGenerator
     */
    private $crudUrlGenerator;
    /**
     * @var AdminContextProvider
     */
    private $adminContextProvider;

    /**
     * RoomTypesCrudController constructor.
     * @param HostelRepository $hostelRepository
     * @param RoomAmenitiesRepository $roomAmenitiesRepository
     * @param Security $security
     * @param AdminMessagesHandler $adminMessagesHandler
     * @param CrudUrlGenerator $crudUrlGenerator
     * @param AdminContextProvider $adminContextProvider
     */
    public function __construct(
        HostelRepository $hostelRepository,
        RoomAmenitiesRepository $roomAmenitiesRepository,
        Security $security,
        AdminMessagesHandler $adminMessagesHandler,
        CrudUrlGenerator $crudUrlGenerator,
        AdminContextProvider $adminContextProvider
    ) {
        $this->hostelRepository = $hostelRepository;
        $this->roomAmenitiesRepository = $roomAmenitiesRepository;
        $this->security = $security;
        $this->adminMessagesHandler = $adminMessagesHandler;
        $this->crudUrlGenerator = $crudUrlGenerator;

        // get the user id from the logged in user
      /*  if (null !== $security->getUser()) {
            $this->user = $security->getUser();
            $this->user_id = $this->user->getId();
            $this->hostels = $this->user->getHostels();
        }

        $this->adminContextProvider = $adminContextProvider;*/
    }

    /**
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return RoomTypes::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_NEW, 'Zimmer anlegen')
            ->setPageTitle(Crud::PAGE_EDIT, 'Zimmer bearbeiten')
            ->setPageTitle(Crud::PAGE_INDEX, 'Zimmer der Unterkünfte')
            ->setDefaultSort(['isUserMadeChanges' => 'DESC']);
    }


    /**
     * Configure the fields to show for the
     * user in the backend
     *
     * @param string $pageName
     * @return iterable
     */
    public function configureFields(string $pageName): iterable
    {
        $required_panel = FormField::addPanel('Mindestangaben');

        $extended_panel = FormField::addPanel('Zusatzangaben')
            ->setHelp('Um so mehr Angaben Sie machen um so besser wird Ihr Angebot bei Google angezeigt.');

        $id = IdField::new('id');
        // The hostel field build the association to the rooms
        $hostel_id = AssociationField::new('hostel', 'Unterkunft')
            ->setCrudController(AdminHostelCrudController::class)
            ->setHelp('Hostel auswählen zu dem ein Zimmer angelegt werden soll')
        ->setSortable(false);

        // Additional booking fee
        $booking_fee = MoneyField::new('booking_fee', 'Zusätzliche Buchungsgebühren')
            ->setCurrency('EUR')
            ->setHelp(
                'Zusätzliche Buchungsgebühren, die die Nutzer dem Werbetreibenden 
                zahlen müssen. Bitte betrachten Sie diese Gebühr als durchschnittliche 
                Gebühr pro Tag für den gesamten Aufenthalt.'
            );

        // If the breakfast include or not
        $breakfast_included = BooleanField::new('breakfast_included', 'Frühstück inclusive');

        // Simply the currency type
        $currency = TextField::new('currency', 'Währung')->setFormType(ChoiceType::class)
            ->setFormTypeOptions(
                [
                    'choices'  => [
                        'Euro'   => 'EUR',
                        'Pfund'  => 'GBP',
                        'Dollar' => 'USD',
                    ],
                    'group_by' => 'id',
                ]
            );

        // Discount array field for additional discounts for this room
        $discounts = ArrayField::new('discounts', 'Rabat Array')
            ->setHelp('Liste der auf den Preis anwendbaren Rabatte');

        // The final price of the room inclusive tax
        $final_rate = MoneyField::new('final_rate', 'Endpreis')
            ->setCurrency('EUR')
            ->setHelp(
                'Der endgültige Preis, den der Benutzer zahlen muss (Rabatte ausgeschlossen). 
                Bitte betrachten Sie diesen Preis als Durchschnittspreis pro Tag für den gesamten Aufenthalt.'
            );

        // Is the cancellation of the room order for free
        $free_cancellation = BooleanField::new('free_cancellation', 'Gratis Stornierung');

        // Additional hotel fee
        $hotel_fee = MoneyField::new('hotel_fee', 'Hotelgebühr')
            ->setCurrency('EUR')
            ->setHelp(
                'Zusätzliche Gebühren, die die Benutzer zahlen müssen, um das Hotel zu bezahlen. 
                Bitte betrachten Sie diese Gebühr als durchschnittliche Gebühr pro Tag für den gesamten Aufenthalt.'
            );

        // The rate model view for showing exclusive stiling for the offer (Exclusive Offer Banner)
        $rate_type = TextField::new('rate_type')
            ->setHelp(
                'Gibt an, ob die Rate exklusiv für Mobilgeräte oder als Prämienrate gilt. 
                Wenn nicht angegeben, wird die Rate als STANDARD betrachtet.'
            )->setFormType(ChoiceType::class)
            ->setFormTypeOptions(
                [
                    'choices'  => [
                        'Standart'   => 'DEFAULT',
                        'Honorieren' => 'REWARD',
                        'Mobil'      => 'MOBILE',
                    ],
                    'group_by' => 'id',
                ]
            );

        // Local tax for the region
        $local_tax = NumberField::new('local_tax')
            ->setHelp(
                'Stadtsteuern. Bitte betrachten Sie diese Gebühr als 
                durchschnittliche Gebühr pro Tag für den gesamten Aufenthalt.'
            );

        // The meale code for the room price and type standard non all inclusive
        $meal_code = TextField::new('meal_code', 'Verpflegungs Code')
            ->setFormType(ChoiceType::class)
            ->setFormTypeOptions(
                [
                    'choices'  => [
                        [
                            'Nur Zimmer'         => 'RO',
                            'Bett und Frühstück' => 'BB',
                            'Halbpension'        => 'HB',
                            'Vollpension'        => 'FB',
                            'Alles Inclusive'    => 'AI',
                        ],
                    ],
                    'group_by' => 'id',
                ]
            );

        // Have the room a individual landing page
        $landing_page_url = UrlField::new('landing_page_url', 'Extra Landingpage Url')
            ->setHelp('Gibt es eine extra Website nur für diese Zimmer Angebot');

        // The netto price of the room exclusive vat and boni
        $net_rate = MoneyField::new('net_rate', 'Netto Preis')
            ->setCurrency('EUR')
            ->setHelp(
                'Nettotarif ohne Steuern. Bitte betrachten Sie diesen Preis 
                als Durchschnittspreis pro Tag für den gesamten Aufenthalt.'
            );

        $payment_type = TextField::new('payment_type', 'Zahlungsmethode')
            ->setFormTypeOptions(
                [
                    'data' => 'Vorkasse bei Ankunft',
                ]
            );

        // Have we a resort fee for this room
        $resort_fee = MoneyField::new('resort_fee', 'Kurtaxe pro Tag')
            ->setCurrency('EUR');


        // The room amenities collection type build from db
        $room_amenities = CollectionField::new('room_amenities', 'Zimmerausstattung')
            ->setHelp('Weicht die Zimmer Ausstattung von der Globalen Unterkunft Ausstattung ab')
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

        $room_code = TextField::new('room_code', 'Zimmernummer / Stellplatznummer')
            ->setHelp('Die Zimmernummer oder Stellplatznummer bei Camping')
            ->setFormTypeOptions(
                [
                    'data' => 'EG',
                ]
            );

        $service_charge = MoneyField::new('service_charge', 'Servicegebühr')
            ->setCurrency('EUR');

        $url = UrlField::new('url', 'Website Url')
            ->setHelp('Die Address zu Ihrer Website');

        $vat = NumberField::new('vat', 'MwSt. Satz')
            ->setFormType(NumberType::class)
            ->setFormTypeOptions(
                [
                    'empty_data' => '19.00',
                    'data'       => '19.00',
                ]
            );

        $is_user_made_changes = BooleanField::new('is_user_made_changes', 'Veränderung');
        $name = TextField::new('name', 'Angebots Name');
        $is_handicapped_accessible = BooleanField::new('is_handicapped_accessible', 'Barrierefrei');

        //Specific accommodation's category. E.g. "Einzelzimmer", "Stellplatz", "Junior Suite"
        $accommodation_type = TextField::new('accommodation_type', 'Kategorie')
            ->setFormType(ChoiceType::class)
            ->setFormTypeOptions(
                [
                    'choices'  => [
                        [
                            'Einzelzimmer'   => 'Einzelzimmer',
                            'Doppelzimmer'   => 'Doppelzimmer',
                            'Mehrbettzimmer' => 'Mehrbettzimmer',
                            'Penthouse'      => 'Penthouse',
                            'Stellplatz'     => 'Stellplatz',
                            'Holzhütte'      => 'Holzhütte',
                            'Junior Suite'   => 'Junior Suite',
                            'Suite'          => 'Suite',
                            'Ferienwohnung'  => 'Ferienwohnung',
                        ],
                    ],
                    'group_by' => 'id',
                ]
            );

        // Number of units the unique partner reference
        $number_of_units = IntegerField::new('number_of_units', 'Anzahl dieses Zimmers')
            ->setHelp('z.b 5 dann wird im Angebot 5x Doppelzimmer für 49 EUR angezeigt');

        // Numeric size of the unit in square feet or meters
        $unit_size = IntegerField::new('unit_size', 'Raum / Platz größe');

        // Square feet or square meters
        $unit_type = TextField::new('unit_type', 'Größen Einheit')
            ->setFormType(ChoiceType::class)
            ->setFormTypeOptions(
                [
                    'choices'  => [
                        [
                            'Quadratmeter' => 'qm',
                            'Square feet'  => 'ft²',
                        ],
                    ],
                    'group_by' => 'id',
                ]
            );

        // Number of guests allowed per unit
        $unit_occupancy = IntegerField::new('unit_occupancy', 'Anzahl Gäste in diesem Zimmer')
            ->setHelp('z.b. 2 dann wird in der Suchfunktion 5 Zimmer x2 also 10 Gäste maximal in der Suche');

        $number_of_bedrooms = NumberField::new('number_of_bedrooms', 'Anzahl Schlafzimmer');
        $number_of_bathrooms = NumberField::new('number_of_bathrooms', 'Anzahl Badezimmer');

        // Number of floor where the unit is located
        $floor_number = IntegerField::new('floor_number', 'Etage');

        // House or apartment number
        $unit_number = TextField::new('unit_number', 'Hausnummer / Apartment');

        switch ($pageName) {
            case Crud::PAGE_INDEX:
                return [
                    $id,
                    $hostel_id,
                    $name,
                    $currency,
                    $vat,
                    $discounts,
                    $final_rate,
                    $net_rate,
                    $payment_type,
                    $url,
                    $meal_code,
                    $is_user_made_changes
                ];
                break;
            case Crud::PAGE_DETAIL:
                return [];
                break;
            case Crud::PAGE_EDIT:
            case Crud::PAGE_NEW:
                return [
                    $required_panel,
                    $hostel_id,
                    $name,
                    $currency,
                    $vat,
                    $final_rate,
                    $payment_type,
                    $meal_code,
                    $accommodation_type,
                    $room_code,
                    $number_of_units,
                    $unit_occupancy,

                    $extended_panel,
                    $is_user_made_changes,
                    $booking_fee,
                    $discounts,
                    $hotel_fee,
                    $rate_type,
                    $local_tax,
                    $net_rate,
                    $resort_fee,
                    $room_amenities,
                    $service_charge,
                    $url,
                    $landing_page_url,
                    $breakfast_included,
                    $free_cancellation,
                    $is_handicapped_accessible,
                    $unit_size,
                    $unit_type,
                    $number_of_bedrooms,
                    $number_of_bathrooms,
                    $floor_number,
                    $unit_number,
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


    /**
     * Create the option array
     * @return array
     */
    protected function buildRoomAmenitiesOptions()
    {

        $options = [];

        // get from db
        $roomAmenities = $this->roomAmenitiesRepository->getRoomAmenitiesWithDescription();

        // build option array
        foreach ($roomAmenities as $roomAmenity) {
            $options[$roomAmenity[0]->getName()] = $roomAmenity['name'];
        }

        return $options;
    }
}
