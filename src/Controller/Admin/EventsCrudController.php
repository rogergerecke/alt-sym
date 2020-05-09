<?php

namespace App\Controller\Admin;

use App\Entity\Events;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class EventsCrudController extends AbstractCrudController
{
    public static $entityFqcn = Events::class;

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
    }

    public function configureFields(string $pageName): iterable
    {


        $id = IdField::new('id');
        $title = TextField::new('title','Titel');
        $short_description = TextareaField::new('short_description','Kurzbeschreibung');
        $description = TextareaField::new('description', 'Beschreibung')->setFormType(CKEditorType::class);
        $address = TextField::new('address','Adresse');
        $latitude = NumberField::new('latitude');
        $longitude = NumberField::new('longitude');
        $user_id = IdField::new('user_id');
        $event_start_date = DateTimeField::new('event_start_date');
        $event_end_date = DateTimeField::new('event_end_date');
        $end_of_advertising = DateTimeField::new('end_of_advertising');
        $create_at = DateField::new('create_at');
        $status = BooleanField::new('status');

        switch ($pageName) {
            case Crud::PAGE_INDEX:
                return [
                    $title,
                    $short_description,
                    $event_start_date,
                    $event_end_date,
                    $end_of_advertising,
                    $create_at,
                ];
            case Crud::PAGE_DETAIL:
                return [
                    $user_id,
                    $id,
                    $title,
                    $short_description,
                    $description,
                    $address,
                    $latitude,
                    $longitude,
                    $event_start_date,
                    $event_end_date,
                    $end_of_advertising,
                    $create_at,
                    $status,
                ];
            case Crud::PAGE_NEW:
            case Crud::PAGE_EDIT:
                return [
                    $title,
                    $short_description,
                    $description,
                    $address,
                    $latitude,
                    $longitude,
                    $event_start_date,
                    $event_end_date,
                    $end_of_advertising,
                    $status,
                    ];
        }


    }

    /**
     *
     * Create a new event with
     * the id from the logged in user
     * a user cant create many event's
     *
     * @param string $entityFqcn
     * @return Events|mixed
     */
    public function createEntity(string $entityFqcn)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $events = new Events();
        $events->setUserId((int)$user->getId());

        return $events;
    }


    public static function getEntityFqcn(): string
    {
        return self::$entityFqcn;
    }
}
