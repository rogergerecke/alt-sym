<?php

namespace App\Controller\Admin;

use App\Entity\RoomTypes;
use App\Repository\HostelRepository;
use App\Repository\RoomAmenitiesRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

/**
 * Class RoomTypesCrudController
 * @package App\Controller\Admin
 */
class RoomTypesCrudController extends AbstractCrudController
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
     * RoomTypesCrudController constructor.
     * @param HostelRepository $hostelRepository
     * @param RoomAmenitiesRepository $roomAmenitiesRepository
     * @param Security $security
     */
    public function __construct(HostelRepository $hostelRepository,RoomAmenitiesRepository $roomAmenitiesRepository, Security $security)
    {
        $this->hostelRepository = $hostelRepository;
        $this->roomAmenitiesRepository = $roomAmenitiesRepository;

        $this->security = $security;

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
        return RoomTypes::class;
    }

    /**
     * @param string $pageName
     * @return iterable
     */
    public function configureFields(string $pageName): iterable
    {

        $id = IdField::new('id');

        // The hostel owner id
        $hostel_id = IntegerField::new('hostel_id', 'Zimmer zu Unterkunft')
            ->setFormType(ChoiceType::class)
            ->setFormTypeOptions(
                [
                    'choices'  => [
                        $this->getHostelData(),
                    ],
                    'group_by' => 'id',
                ]
            )
            ->setHelp('Hostel auswählen zu dem ein Zimmer angelegt werden soll');

        // Additional booking fee
        $booking_fee = MoneyField::new('booking_fee', 'Zusätzliche Buchungsgebühren')
            ->setCurrency('EUR')
            ->setHelp(
                'Zusätzliche Buchungsgebühren, die die Nutzer dem Werbetreibenden zahlen müssen. Bitte betrachten Sie diese Gebühr als durchschnittliche Gebühr pro Tag für den gesamten Aufenthalt.'
            );

        // If the breakfast include or not
        $breakfast_included = BooleanField::new('breakfast_included', 'Frühstück inclusive');

        // Simply the currency type
        $currency = TextField::new('currency', 'Währung')->setFormType(ChoiceType::class)
            ->setFormTypeOptions(
                [
                    'choices'  => [
                        'Euro' => 'EUR',
                        'Pfund' => 'GBP',
                        'Dollar' => 'USD',
                    ],
                    'group_by' => 'id',
                ]
            );

        // todo discount
        // Discount array field for additional discounts for this room
        $discounts = ArrayField::new('discounts', 'Rabat Array')
            ->setHelp('Liste der auf den Preis anwendbaren Rabatte');

        // The final price of the room inclusive tax
        $final_rate = MoneyField::new('final_rate', 'Endpreis')
            ->setCurrency('EUR')
            ->setHelp(
                'Der endgültige Preis, den der Benutzer zahlen muss (Rabatte ausgeschlossen). Bitte betrachten Sie diesen Preis als Durchschnittspreis pro Tag für den gesamten Aufenthalt.'
            );

        // Is the cancellation of the room order for free
        $free_cancellation = BooleanField::new('free_cancellation', 'Gratis Stornierung');

        // Additional hotel fee
        $hotel_fee = MoneyField::new('hotel_fee', 'Hotelgebühr')
            ->setCurrency('EUR')
            ->setHelp(
                'Zusätzliche Gebühren, die die Benutzer zahlen müssen, um das Hotel zu bezahlen. Bitte betrachten Sie diese Gebühr als durchschnittliche Gebühr pro Tag für den gesamten Aufenthalt.'
            );

        // The rate model view for showing exclusive stiling for the offer (Exclusive Offer Banner)
        $rate_type = TextField::new('rate_type')
            ->setHelp(
                'Gibt an, ob die Rate exklusiv für Mobilgeräte oder als Prämienrate gilt. Wenn nicht angegeben, wird die Rate als STANDARD betrachtet.'
            )->setFormType(ChoiceType::class)
            ->setFormTypeOptions(
                [
                    'choices'  => [
                        'Standart' => 'DEFAULT',
                        'Honorieren' => 'REWARD',
                        'Mobil' => 'MOBILE',
                    ],
                    'group_by' => 'id',
                ]
            );

        // Local tax for the region
        $local_tax = NumberField::new('local_tax')
            ->setHelp(
                'Stadtsteuern. Bitte betrachten Sie diese Gebühr als durchschnittliche Gebühr pro Tag für den gesamten Aufenthalt.'
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
            ->setHelp('Gibt es eine extra Website nur für diese Zimmer');

        // The netto price of the room exclusive vat and boni
        $net_rate = MoneyField::new('net_rate', 'Netto Preis')
            ->setCurrency('EUR')
            ->setHelp(
                'Nettotarif ohne Steuern. Bitte betrachten Sie diesen Preis als Durchschnittspreis pro Tag für den gesamten Aufenthalt.'
            );

        $payment_type = TextField::new('payment_type', 'Zahlungsmethode');

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
            ->setHelp('Die Zimmernummer oder Stellplatznummer bei Camping');

        $service_charge = MoneyField::new('service_charge', 'Servicegebühr')
            ->setCurrency('EUR');

        $url = UrlField::new('url', 'Website Url')
            ->setHelp('Die Address zu Ihrer Website');

        $vat = NumberField::new('vat', 'MwSt. Satz')
            ->setFormType(NumberType::class)
            ->setFormTypeOptions(
                [
                    'empty_data' => '19.00',
                ]
            );

        switch ($pageName) {
            case Crud::PAGE_INDEX:
                return [
                    $hostel_id,
                    $currency,
                    $vat,
                    $discounts,
                    $final_rate,
                    $net_rate,
                    $payment_type,
                    $url,
                    $meal_code,
                    ];
                break;
            case Crud::PAGE_DETAIL:
                return [];
                break;
            case Crud::PAGE_EDIT:
            case Crud::PAGE_NEW:
                return [
                    $hostel_id,
                    $booking_fee,
                    $currency,
                    $vat,
                    $discounts,
                    $final_rate,
                    $hotel_fee,
                    $rate_type,
                    $local_tax,
                    $net_rate,
                    $payment_type,
                    $resort_fee,
                    $room_amenities,
                    $room_code,
                    $service_charge,
                    $url,
                    $landing_page_url,
                    $meal_code,
                    $breakfast_included,
                    $free_cancellation,
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
     * @return RoomTypes|mixed
     */
    public function createEntity(string $entityFqcn)
    {

        $room_types = new RoomTypes();
        $room_types->setIsUserMadeChanges(true);

        return $room_types;
    }



    ##########################################################
    #
    #
    #   Protected Helper Function
    #
    #
    ##########################################################

    /**
     * @return array
     */
    protected function getHostelData()
    {
        $hostels = $this->hostelRepository->findBy(['user_id'=>$this->user_id]);

        $option = null;

        foreach ($hostels as $hostel) {
            $name = 'ID: '.$hostel->getId().', '.stripslashes($hostel->getHostelName());
            $option[$name] = $hostel->getId();
        }

        if ($option === null){
            $this->addFlash('danger','Sie haben Noch kein Hostel angelegt');
        }


        return $option;
    }

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
