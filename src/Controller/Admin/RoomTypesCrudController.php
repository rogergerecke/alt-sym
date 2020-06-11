<?php

namespace App\Controller\Admin;

use App\Entity\RoomTypes;
use App\Repository\HostelRepository;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
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

class RoomTypesCrudController extends AbstractCrudController
{

    /**
     * @var HostelRepository
     */
    private $hostelRepository;

    public function __construct(HostelRepository $hostelRepository)
    {
        $this->hostelRepository = $hostelRepository;
    }

    public static function getEntityFqcn(): string
    {
        return RoomTypes::class;
    }

    public function configureFields(string $pageName): iterable
    {

        $id = IdField::new('id');

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

        $booking_fee = MoneyField::new('booking_fee', 'Zusätzliche Buchungsgebühren')
            ->setCurrency('EUR')
            ->setHelp(
                'Zusätzliche Buchungsgebühren, die die Nutzer dem Werbetreibenden zahlen müssen. Bitte betrachten Sie diese Gebühr als durchschnittliche Gebühr pro Tag für den gesamten Aufenthalt.'
            );

        $breakfast_included = BooleanField::new('breakfast_included', 'Frühstück inclusive');

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

        $discounts = ArrayField::new('discounts', 'Rabat Array')
            ->setHelp('Liste der auf den Preis anwendbaren Rabatte');

        $final_rate = MoneyField::new('final_rate', 'Endpreis')
            ->setCurrency('EUR')
            ->setHelp(
                'Der endgültige Preis, den der Benutzer zahlen muss (Rabatte ausgeschlossen). Bitte betrachten Sie diesen Preis als Durchschnittspreis pro Tag für den gesamten Aufenthalt.'
            );

        $free_cancellation = BooleanField::new('free_cancellation', 'Gratis Stornierung');

        $hotel_fee = MoneyField::new('hotel_fee', 'Hotelgebühr')
            ->setCurrency('EUR')
            ->setHelp(
                'Zusätzliche Gebühren, die die Benutzer zahlen müssen, um das Hotel zu bezahlen. Bitte betrachten Sie diese Gebühr als durchschnittliche Gebühr pro Tag für den gesamten Aufenthalt.'
            );

        $rate_type = TextField::new('rate_type')
            ->setHelp(
                'Gibt an, ob die Rate exklusiv für Mobilgeräte oder als Prämienrate gilt. Wenn nicht angegeben, wird die Rate als STANDARD betrachtet.'
            );

        $local_tax = NumberField::new('local_tax')
            ->setHelp(
                'Stadtsteuern. Bitte betrachten Sie diese Gebühr als durchschnittliche Gebühr pro Tag für den gesamten Aufenthalt.'
            );

        //
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

        $landing_page_url = UrlField::new('landing_page_url', 'Extra Landingpage Url')
            ->setHelp('Gibt es eine extra Website nur für diese Zimmer');

        $net_rate = MoneyField::new('net_rate', 'Netto Preis')
            ->setCurrency('EUR')
            ->setHelp(
                'Nettotarif ohne Steuern. Bitte betrachten Sie diesen Preis als Durchschnittspreis pro Tag für den gesamten Aufenthalt.'
            );

        $payment_type = TextField::new('payment_type', 'Zahlungsmethode');
        $resort_fee = MoneyField::new('resort_fee', 'Kurtaxe pro Tag')
            ->setCurrency('EUR');

        $room_amenities = ArrayField::new('room_amenities', 'Zimmerausstattung');
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

    protected function getHostelData()
    {
        $hostels = $this->hostelRepository->findAll();

        $option = null;

        foreach ($hostels as $hostel) {
            $name = 'ID: '.$hostel->getId().', '.stripslashes($hostel->getHostelName());
            $option[$name] = $hostel->getId();
        }

        return $option;
    }

}
