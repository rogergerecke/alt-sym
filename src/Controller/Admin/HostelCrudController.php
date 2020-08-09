<?php

namespace App\Controller\Admin;

use App\Entity\Hostel;
use App\Entity\RoomAmenities;
use App\Repository\AmenitiesTypesRepository;
use App\Repository\CountrysRepository;
use App\Repository\CurrencyRepository;
use App\Repository\FederalStateRepository;
use App\Repository\HostelRepository;
use App\Repository\RoomAmenitiesRepository;
use App\Repository\UserRepository;
use App\Service\AdminMessagesHandler;
use App\Service\SystemOptionsService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
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
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use FM\ElfinderBundle\Form\Type\ElFinderType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class HostelCrudController
 * @package App\Controller\Admin
 */
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
    /**
     * @var
     */
    private $user_id;
    /**
     * @var AdminMessagesHandler
     */
    private $adminMessagesHandler;
    /**
     * @var CrudUrlGenerator
     */
    private $crudUrlGenerator;
    /**
     * @var SystemOptionsService
     */
    private $systemOptions;
    /**
     * @var UserInterface|null
     */
    private $user;
    /**
     * @var Swift_Mailer
     */
    private $mailer;
    /**
     * @var HostelRepository
     */
    private $hostelRepository;

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
     * @param AdminMessagesHandler $adminMessagesHandler
     * @param CrudUrlGenerator $crudUrlGenerator
     * @param SystemOptionsService $systemOptions
     * @param Swift_Mailer $mailer
     * @param HostelRepository $hostelRepository
     */
    public function __construct(
        UserRepository $userRepository,
        RoomAmenitiesRepository $roomAmenities,
        CountrysRepository $countrysRepository,
        CurrencyRepository $currencyRepository,
        FederalStateRepository $federalStateRepository,
        AmenitiesTypesRepository $amenitiesTypesRepository,
        Security $security,
        EntityManagerInterface $em,
        AdminMessagesHandler $adminMessagesHandler,
        CrudUrlGenerator $crudUrlGenerator,
        SystemOptionsService $systemOptions,
        Swift_Mailer $mailer,
    HostelRepository $hostelRepository
    ) {
        $this->userRepository = $userRepository;
        $this->roomAmenities = $roomAmenities;
        $this->countrysRepository = $countrysRepository;
        $this->currencyRepository = $currencyRepository;
        $this->federalStateRepository = $federalStateRepository;
        $this->amenitiesTypesRepository = $amenitiesTypesRepository;
        $this->security = $security;

        // get the user id from the logged in user
        if (null !== $this->security->getUser()) {
            $this->user = $this->security->getUser();
            $this->user_id = $this->security->getUser()->getId();
        }

        $this->em = $em;
        $this->adminMessagesHandler = $adminMessagesHandler;
        $this->crudUrlGenerator = $crudUrlGenerator;
        $this->systemOptions = $systemOptions;
        $this->mailer = $mailer;
        $this->hostelRepository = $hostelRepository;
    }

    /**
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return Hostel::class;
    }

    /**
     * Override the edit entity function
     * to prevent entity id hack by false user
     *
     * @param AdminContext $context
     * @return KeyValueStore|RedirectResponse|Response
     */
    public function edit(AdminContext $context)
    {
        // get all ids for the user
        $ids = null;
        if ($hostels = $this->hostelRepository->findBy(['user_id' => $this->user_id])) {
            foreach ($hostels as $hostel) {
                $ids[] = $hostel->getId();
            }
        }

        // no ids for user
        if (!$ids) {
            $this->addFlash('warning', 'Sie haben noch keine Hostels angelegt');
            $this->redirectToRoute('user');
        }

        // permission denied url entity hack
        if (!in_array($context->getEntity()->getPrimaryKeyValue(), $ids)) {
            $this->addFlash('warning', 'Sie dürfen keine Fremden Hostels bearbeiten');
            $this->redirectToRoute('user');
        } else {
            return parent::edit($context);
        }

        // return empty object
        return $this->render(
            'bundles/EasyAdmin/crazy_horse.html.twig',
            [

            ]
        );
    }

    /**
     * Override the delete entity function
     * to prevent entity id hack by false user
     *
     * @param AdminContext $context
     * @return KeyValueStore|RedirectResponse|Response
     */
    public function delete(AdminContext $context)
    {
        // get all ids for the user
        $ids = null;
        if ($hostels = $this->hostelRepository->findBy(['user_id' => $this->user_id])) {
            foreach ($hostels as $hostel) {
                $ids[] = $hostel->getId();
            }
        }

        // no ids for user
        if (!$ids) {
            $this->addFlash('warning', 'Sie haben noch keine Hostels angelegt, darum können Sie auch keine Löschen');
            $this->redirectToRoute('user');
        }

        // permission denied url entity hack
        if (!in_array($context->getEntity()->getPrimaryKeyValue(), $ids)) {
            $this->addFlash('warning', 'Sie dürfen keine Fremden Hostels löschen');
            $this->redirectToRoute('user');
        } else {
            return parent::delete($context);
        }

        // return empty object
        return $this->render(
            'bundles/EasyAdmin/crazy_horse.html.twig',
            [

            ]
        );
    }

    /**
     * Modified index builder to show only
     * hostels for the logged in user on
     * index table
     *
     * @param SearchDto $searchDto
     * @param EntityDto $entityDto
     * @param FieldCollection $fields
     * @param FilterCollection $filters
     * @return QueryBuilder
     */
    public function createIndexQueryBuilder(
        SearchDto $searchDto,
        EntityDto $entityDto,
        FieldCollection $fields,
        FilterCollection $filters
    ): QueryBuilder {

        $qb = $this->get(EntityRepository::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $alias = $qb->getRootAliases();
        $qb->andWhere($alias[0].'.user_id = '.$this->user_id);

        return $qb;
    }


    /**
     * @param Crud $crud
     * @return Crud
     */
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->addFormTheme('@FMElfinderBundle/Form/elfinder_widget.html.twig')
            ->setPageTitle(Crud::PAGE_INDEX, 'Ihre Unterkünfte')
            ->setPageTitle(Crud::PAGE_NEW, 'Unterkunft anlegen')
            ->setHelp(
                Crud::PAGE_NEW,
                'Hier können Sie Ihre Unterkunft anlegen.  Die Felder mit rotem Punkt sind Pflichtfelder.'
            );
    }


    /**
     * @param string $pageName
     * @return iterable
     */
    public function configureFields(string $pageName): iterable
    {

        // id fields
        /* $id = IdField::new('id');*/
//        $user_id = AssociationField::new('user')
//            ->setFormTypeOptions(
//                [
//                'query_builder' => function (UserRepository $ur) {
//                    return $ur->createQueryBuilder('u')
//                        ->andWhere("u.id = $this->user_id");
//                },
//                ]
//            );
//
//        // data fields
//        $hostel_name = TextField::new('hostel_name', 'Unterkunft');
//
//        $image = TextField::new('image', 'Erstes Bild')
//            ->setFormType(ElFinderType::class)
//            ->setFormTypeOptions(['instance' => 'banner', 'enable' => true]);
//
//        $address = TextField::new('address', 'Straße');
//        $address_sub = TextField::new('address_sub', 'Adress zusatz');
//        $postcode = IntegerField::new('postcode', 'PLZ');
//        $city = TextField::new('city', 'Stadt');
//
//        /* Build the federal state dropdown field SH, NRW */
//        $state = TextField::new('state', 'Bundesland')->setHelp(
//            'In welchem Bundesland liegt Ihre Unterkunft'
//        )
//            ->setFormType(ChoiceType::class)
//            ->setFormTypeOptions(
//                [
//                    'choices'  => [
//                        $this->buildFederalStateOptions(),
//                    ],
//                    'group_by' => 'id',
//                ]
//            );
//
//        $country = TextField::new('country', 'Land');// todo add dropdown
//        $country_id = IntegerField::new('country_id'); // intern filed only
//
//        $longitude = NumberField::new('longitude');
//        $latitude = NumberField::new('latitude');
//        $phone = TextField::new('phone', 'Festnezt');
//        $mobile = TextField::new('mobile');
//        $fax = TextField::new('fax');
//        $web = UrlField::new('web');
//        $email = EmailField::new('email');
//
//        /* Build the currency dropdown field EUR, GBP */
//        $currency = TextField::new('currency', 'Währung')->setHelp(
//            'Die Währung in der Sie abrechnen'
//        )
//            ->setFormType(ChoiceType::class)
//            ->setFormTypeOptions(
//                [
//                    'choices'  => [
//                        $this->buildCurrencyOptions(),
//                    ],
//                    'group_by' => 'id',
//                ]
//            );
//
//        /* amenities choices array NO_SMOKING, W-LAN*/
//        $amenities = CollectionField::new('amenities', 'Ausstattung')->setHelp(
//            'Ausstattung die generell im Hostel verfügbar ist'
//        )
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
//        $description = TextEditorField::new('description', 'Beschreibung');
//
//        // api connection parameter for hostel availability import autogenerated
//        $api_key = TextField::new('api_key')->setHelp(
//            'Ihr API schlüssel für den Import'
//        )->setFormTypeOption('disabled', true);
//
//        $hostel_availability_url = UrlField::new('hostel_availability_url')->setHelp(
//            'URL für den Import der Zimmerverfügbarkeit. Abruf 1x Stündlich.'
//        );
//
//        // Extra cost field only by admin editable
//        $sort = IntegerField::new('sort');
//        $startpage = BooleanField::new('startpage');
//        $toplisting = BooleanField::new('toplisting');
//        $top_placement_finished = DateTimeField::new('top_placement_finished');
//
//        // Hostel on or offline switch
//        $status = BooleanField::new('status');
//
//        $hostel_type = TextField::new('hostel_type', 'Unterkunft-Typ')
//            ->setFormType(ChoiceType::class)
//            ->setFormTypeOptions(
//                [
//                    'choices'  => [
//                        $this->buildAmenitiesTypesOptions(),
//                    ],
//                    'group_by' => 'id',
//                ]
//            );

        // The user field build the association to the hostels
        $id = IdField::new('id');
        $user_id = AssociationField::new('user', 'Benutzerprofil')
            ->setRequired(true)
            ->setSortable(false)
            ->setFormTypeOptions(
                [
                    'query_builder' => function (UserRepository $ur) {
                        return $ur->createQueryBuilder('u')
                            ->andWhere("u.id = $this->user_id");
                    },
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
            );

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
        /* $sort = IntegerField::new('sort', 'Sortierung');
         $startpage = BooleanField::new('startpage', 'Auf Startseite einblenden');
         $toplisting = BooleanField::new('toplisting', 'Top-Platzierung außerhalb der Pakete');*/
        $top_placement_finished = DateTimeField::new('top_placement_finished', 'Ende der Top-Platzierung')
            ->setFormTypeOption('disabled', true);

        // Hostel on or offline switch
        /* $status = BooleanField::new('status', 'Online');*/
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
                    $id,
                    $hostel_name,
                    $preview_image,
                    $address,
                    $postcode,
                    $city,
                ];
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
                    $top_placement_finished,
                ];
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
     * Return a array with the select options
     * for the federal state selection field
     *
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
     * Return a array with the select options
     * for the currency selection field
     *
     * @return array
     */
    protected function buildCurrencyOptions()
    {
        $options = [];

        $currencies = $this->currencyRepository->findBy(['status' => true], ['sort' => 'ASC']);

        foreach ($currencies as $currency) {
            $options[$currency->getName()] = $currency->getCode();
        }

        return $options;
    }

    ##########################################################
    #
    #
    #   Protected Helper Function
    #
    #
    ##########################################################

    /**
     * Return a array with the select options
     * for the room amenities selection field
     *
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
     * Return a array with the select options
     * for the amenities types selection field
     *
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

            // add message to admin log
            $this->adminMessagesHandler->addInfo(
                "Der Benutzer-ID: $this->user_id hat an einer Unterkunft einstellung geändert.",
                "Ein Benutzer hat eine Unterkunft bearbeitet."
            );
        }

        parent::updateEntity($entityManager, $entityInstance);
    }

    /**
     * Create a new hostel with the id from
     * the logged in user a user cant have
     * many hostel's
     *
     * @param string $entityFqcn
     * @return Hostel|mixed
     * @throws TransportExceptionInterface
     */
    public function createEntity(string $entityFqcn)
    {

        $user = $this->userRepository->findOneBy(['email' => $this->getUser()->getUsername()]);

        // set default values
        if (null !== $user) {
            $hostel = new Hostel();
            $hostel->setUserId($user->getId());
            $hostel->setIsUserMadeChanges(true);
            $hostel->setStartpage(false);
            $hostel->setToplisting(false);
            $hostel->setStatus(false);
            $hostel->setSort(100);
            $hostel->setAmenities(['non_smoking']);

            // add message to admin log
            $this->adminMessagesHandler->addInfo(
                "Der Benutzer-ID: ".$user->getName()." hat eine neue Unterkunft angelegt.",
                "Es wurde eine neue Unterkunft angelegt."
            );

            $url = $this->createEditUrl($hostel->getId());

            // build vars for information admin mail
            $email_template_vars = [
                'web_site_name'          => $this->systemOptions->getWebSiteName(),
                'user_hostel_edit_url'   => $url,
                'user_number'            => $this->user->getPartnerId(),
                'user_name'              => $this->user->getName(),
                'user_email'             => $this->user->getUsername(),
                'user_registration_date' => $this->user->getCreateAt()->format('d.m.Y'),

            ];
            $this->sendInfoAdminMail($email_template_vars);

            return $hostel;
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
     * Build the User-Profil Edit Url for the admin
     * @param $id
     * @return CrudUrlBuilder
     */
    protected function createEditUrl($id): string
    {
        return $this->crudUrlGenerator->build()
            ->setDashboard(AdminDashboardController::class)
            ->setController(AdminHostelCrudController::class)
            ->setAction(Action::EDIT)
            ->setEntityId($id);
    }

    /**
     * Send a information mail to admin
     *
     * @param array $email_template_vars
     */
    protected function sendInfoAdminMail(array $email_template_vars): void
    {
        // upgrade massage for the system admin
        // do anything else you need here, like send an email
        $message = new Swift_Message('Altmühlsee Admin Aktion nötig');

        // send a copy to
        if (null !== ($this->systemOptions->getCopiedReviverEmailAddress())) {
            $message->setCc($this->systemOptions->getCopiedReviverEmailAddress());
        }

        // if developer mode send mails to the developer
        if (null !== ($this->systemOptions->getTestEmailAddress())) {
            $message->setTo($this->systemOptions->getTestEmailAddress());
        } else {
            // real receiver email address from configuration
            $message->setTo($this->systemOptions->getSupportEmailAddress());
        }

        $message
            ->setFrom($this->systemOptions->getMailSystemAbsenceAddress())
            ->setBody(
                $this->renderView(
                // Email-Template templates/emails/new_hostel_entry.html.twig
                    'emails/new_hostel_entry.html.twig',
                    $email_template_vars
                ),
                'text/html'
            );

        $this->mailer->send($message);
    }
}
