<?php

namespace App\Controller\Admin;

use App\Entity\Events;
use App\Repository\EventsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class EventsCrudController
 * @package App\Controller\Admin
 */
class AdminEventsCrudController extends AbstractCrudController
{

    /**
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return Events::class;
    }



    /**
     * @param Crud $crud
     * @return Crud
     */
    public function configureCrud(Crud $crud): Crud
    {
        return $crud->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig')
            ->setPageTitle(Crud::PAGE_EDIT, 'Veranstaltung')
            ->setPageTitle(Crud::PAGE_INDEX, 'Veranstaltung')
            ->setPageTitle(Crud::PAGE_NEW, 'Veranstaltung')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Veranstaltung')
            ->setHelp(
                Crud::PAGE_EDIT,
                'Tragen Sie zu Ihrer Veranstaltung auch die Geo-Position
                 ein von Google Maps aus der URL z.b. :@49.1345911,10.7017359
                  dies ist die Position für den Altmühlsee.'
            );
    }

    /**
     * @param string $pageName
     * @return iterable
     */
    public function configureFields(string $pageName): iterable
    {

        $id = IdField::new('id');
        $title = TextField::new('title', 'Titel');
        $short_description = TextareaField::new('short_description', 'Kurzbeschreibung');

        $description = TextareaField::new('description', 'Beschreibung')
            ->setFormType(CKEditorType::class);

        $address = TextField::new('address', 'Adresse')
            ->setHelp('Wenn nur die Adresse angegeben wird, wird mit dieser Versucht die Geo-Position zu ermitteln');

        $latitude = NumberField::new('latitude', 'Google-Maps Latitude')
            ->setHelp(
                'Geo-Position Latitude erster Teil nach dem @<b>49.1345911</b>,10.7017359 Zeichen in der Maps-Url'
            );
        $longitude = NumberField::new('longitude', 'Google-Maps Longitude')
            ->setHelp(
                'Geo-Position Latitude zweiter Teil nach dem @49.1345911,<b>10.7017359</b> Zeichen in der Maps-Url'
            );

        $user_id = IdField::new('user_id');
        $event_start_date = DateTimeField::new('event_start_date', 'Wann beginnt die Veranstaltung');
        $event_end_date = DateTimeField::new('event_end_date', 'Wann endet die Veranstaltung');
        $end_of_advertising = DateTimeField::new('end_of_advertising', 'Ende der Werbeanzeige');
        $create_at = DateField::new('create_at', 'Erstellt am');
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
     * Create a new Events with
     * the id from the logged in user
     * a user cant have many Event's
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
}
