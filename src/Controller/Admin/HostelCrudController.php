<?php

namespace App\Controller\Admin;

use App\Entity\Hostel;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class HostelCrudController extends AbstractCrudController
{
    public static $entityFqcn = Hostel::class;


    public function configureFields(string $pageName): iterable
    {

        // create fields
        $id = IdField::new('id');
        $hostel_id = TextField::new('hostel_id');
        $partner_id = TextField::new('partner_id','Kundennummer');
        $hostel_name = TextField::new('hostel_name','Name');
        $address = TextField::new('address','Straße');
        $postcode = TextField::new('postcode','PLZ');
        $city = TextField::new('city','Stadt');

        $status = BooleanField::new('status');



        // output fields by page
        if (Crud::PAGE_INDEX === $pageName) {
            return [$hostel_id, $partner_id, $hostel_name, $address, $postcode,$city,$status];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $hostel_id, $partner_id, $hostel_name, $address, $postcode];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$id, $hostel_id, $partner_id, $hostel_name, $address, $postcode];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$id, $hostel_id, $partner_id, $hostel_name, $address, $postcode];
        }
    }

}
