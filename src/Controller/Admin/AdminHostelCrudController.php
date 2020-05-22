<?php

namespace App\Controller\Admin;

use App\Entity\Hostel;
use App\Entity\RoomAmenities;
use App\Entity\User;
use App\Repository\RoomAmenitiesRepository;
use App\Repository\UserRepository;
use Doctrine\DBAL\Types\ArrayType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Configurator\SelectConfigurator;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\ChoiceList\ArrayChoiceList;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;

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

    public function __construct(UserRepository $userRepository,RoomAmenitiesRepository $roomAmenities)
    {
        $this->userRepository = $userRepository;
        $this->roomAmenities = $roomAmenities;
        $this->buildRoomAmenitiesOptions();
    }

    public static function getEntityFqcn(): string
    {
        return Hostel::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('new','Hostel Manager')
            ->setHelp('new','Hier ein Hostel für ein Benutzer anlegen.')
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
    }


    public function configureFields(string $pageName): iterable
    {

        // id fields
        $id = IdField::new('id');
        $user_id = IdField::new('user_id');

        // data fields
        $hostel_name = TextField::new('hostel_name', 'Name');
        $address = TextField::new('address', 'Straße');
        $address_sub = TextField::new('address_sub', 'Adress zusatz');
        $postcode = IntegerField::new('postcode', 'PLZ');
        $city = TextField::new('city', 'Stadt');
        $state = TextField::new('state', 'Bundesland'); // todo add dropdown
        $country = TextField::new('country', 'Land');// todo add dropdown
        $country_id = IntegerField::new('country_id'); // intern filed only
        $longitude = NumberField::new('longitude');
        $latitude = NumberField::new('latitude');
        $phone = TextField::new('phone', 'Festnezt');
        $mobile = TextField::new('mobile');
        $fax = TextField::new('fax');
        $web = UrlField::new('web');
        $email = EmailField::new('email');
        $currency = TextField::new('currency');//todo dropdown
        $room_types = TextField::new('room_types');// todo dropdown array[]

        /* amenities choices array */
        $amenities = CollectionField::new('amenities','Ausstattung')->setHelp('Ausstattung die generell im Hostel verfügbar ist')
            ->setEntryType(ChoiceType::class)
            ->setFormTypeOptions(
                ['entry_options'  => [
                    'choices'  => [
                        $this->buildRoomAmenitiesOptions()
                    ],
                    'label'      => false,
                    'group_by'   => 'id',
                ],
                    ]
            );//todo json*/

        $description = TextareaField::new('description', 'Beschreibung')
            ->setFormType(CKEditorType::class);

        // api connection parameter for hostel availability import
        $api_key = TextField::new('api_key');// todo only display autogenarate UUid
        $hostel_availability_url = UrlField::new('hostel_availability_url');

        // Extra cost field only by admin editable
        $sort = TextField::new('sort'); //todo add only by admin over extra pay
        $startpage = BooleanField::new('startpage');
        $toplisting = BooleanField::new('toplisting');
        $top_placement_finished = DateTimeField::new('top_placement_finished');

        // Hostel on or offline switch
        $status = BooleanField::new('status');


        // output fields by page
        if (Crud::PAGE_INDEX === $pageName) {
            return [$user_id, $hostel_name, $address, $postcode, $city, $status];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [
                $id,
                $user_id,
                $hostel_name,
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
                $hostel_name,
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
                $startpage,
                $toplisting,
                $top_placement_finished,
                $status,
            ];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [
                $hostel_name,
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
                $startpage,
                $toplisting,
                $top_placement_finished,
                $status,
            ];
        }
    }


    public function configureActions(Actions $actions): Actions
    {

        return parent::configureActions($actions); // TODO: Change the autogenerated stub
    }

    # Helper function protected

    protected function buildRoomAmenitiesOptions(){

        $options = [];

        // get from db
        $roomAmenities = $this->roomAmenities->getRoomAmenitiesWithDescription();

        // build option array
        foreach ($roomAmenities as $roomAmenity){
            $options[$roomAmenity[0]->getName()] = $roomAmenity['name'];
        }

        return $options;
    }

}
