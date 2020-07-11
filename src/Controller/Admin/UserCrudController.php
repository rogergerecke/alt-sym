<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Service\AdminMessagesHandler;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Exception\ForbiddenActionException;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use http\Env\Request;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

/**
 * Class UserCrudController
 * @package App\Controller\Admin
 */
class UserCrudController extends AbstractCrudController
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var Security
     */
    private $security;

    /**
     * @var
     */
    private $user_id;
    /**
     * @var AdminMessagesHandler
     */
    private $adminMessagesHandler;

    /**
     * UserCrudController constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param Security $security
     * @param AdminMessagesHandler $adminMessagesHandler
     */
    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        Security $security,
        AdminMessagesHandler $adminMessagesHandler
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->security = $security;

        // save status updates for the admin
        $this->adminMessagesHandler = $adminMessagesHandler;

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
        return User::class;
    }


    /**
     * @param Crud $crud
     * @return Crud
     */
    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityPermission('ROLE_USER');
    }


    /**
     * Manipulate the viewed action in User entity
     *
     * @param Actions $actions
     * @return Actions
     */
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_DETAIL, Action::DELETE)
            ->remove(Crud::PAGE_DETAIL, Action::INDEX);
    }

    #############################################
    #
    #
    # Protect the entity action
    #
    #
    #############################################

    /**
     * Protect the entity for edit by user id
     *
     * @param AdminContext $context
     * @return KeyValueStore|RedirectResponse|Response
     */
    public function edit(AdminContext $context)
    {
        // the logged in user query a entity with no permission for him
        if ($this->user_id != $context->getRequest()->get('entityId')) {
            $this->addFlash('warning', 'You have no access');

            return new RedirectResponse($this->generateUrl('user'));

        }

        return parent::edit($context);
    }

    /* public function index(AdminContext $context)
     {
         /* return parent::index($context); // TODO: Change the autogenerated stub
     }*/


    /**
     * @param string $pageName
     * @return iterable
     */
    public function configureFields(string $pageName): iterable
    {


        $id = IdField::new('id');
        $email = TextField::new('email');

        $password = TextField::new('password')->setFormType(PasswordType::class);

        $partner_id = IntegerField::new('partner_id', 'Kundennummer');
        $name = TextField::new('name', 'Ganzer Name');
        $status = BooleanField::new('status', 'Account Online');
        $id = IntegerField::new('id', 'ID');

        switch ($pageName) {
            case Crud::PAGE_INDEX:
                return [
                    $id,
                    $email,
                    $partner_id,
                    $name,

                ];
                break;
            case Crud::PAGE_DETAIL:
                return [

                    $name,
                    $partner_id,
                    $email,
                    $password,
                    $status,
                    $id,
                ];
                break;
            case Crud::PAGE_NEW:
                return [

                ];
                break;
            case Crud::PAGE_EDIT:
                return [
                    $email,
                    $password,
                    $name,
                ];
                break;
        }

    }


    /**
     * If the user make changes on a entity entry
     * so wee set the new state of Entry and
     * add a Admin Message
     *
     * @param EntityManagerInterface $entityManager
     * @param $entityInstance
     */
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // set status toggle after change
        if (method_exists($entityInstance, 'setIsUserMadeChanges')) {
            // yes user have made changes
            $entityInstance->setIsUserMadeChanges(true);

            // save more information for the admin
            $this->adminMessagesHandler->addInfo(
                "Der Benutzer ".$this->getUser()->getUsername()." hat änderung an seinem Profil vorgenommen."
            );
        }

        // set new password with encoder interface
        if (method_exists($entityInstance, 'setPassword')) {
            $clearPassword = $this->get('request_stack')->getCurrentRequest()->request->all('User')['password'];
            $encodedPassword = $this->passwordEncoder->encodePassword($this->getUser(), $clearPassword);
            $entityInstance->setPassword($encodedPassword);
        }

        parent::updateEntity($entityManager, $entityInstance);
    }

}
