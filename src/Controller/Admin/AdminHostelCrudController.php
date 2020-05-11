<?php

namespace App\Controller\Admin;

use App\Entity\Hostel;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AdminHostelCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Hostel::class;
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
        $amenities = ArrayField::new('amenities');
         /*   ->setFormType(CollectionType::class)
            ->setFormTypeOptions(
                ['amenities', CollectionType::class, [
                    'entry_type'   => ChoiceType::class,
                    'entry_options'  => [
                        'choices'  => [
                            'Nashville' => 'nashville',
                            'Paris'     => 'paris',
                            'Berlin'    => 'berlin',
                            'London'    => 'london',
                        ],
                    ],
                ]]
            );//todo json*/

        $description = TextEditorField::new('description', 'Beschreibung');

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
                $status,
            ];
        }
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
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $hostel = new Hostel();
        $hostel->setUserId((int)$user->getId());

        return $hostel;
    }


    public function configureActions(Actions $actions): Actions
    {

        return parent::configureActions($actions); // TODO: Change the autogenerated stub
    }


}
