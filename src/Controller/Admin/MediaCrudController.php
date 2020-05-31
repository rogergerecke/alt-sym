<?php

namespace App\Controller\Admin;

use App\Entity\Media;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FM\ElfinderBundle\Form\Type\ElFinderType;

class MediaCrudController extends AbstractCrudController
{
    public static $entityFqcn = Media::class;
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->addFormTheme('@FMElfinderBundle/Form/elfinder_widget.html.twig')
            ->setPageTitle(Crud::PAGE_NEW,'Bilder eintragen')
            ->setHelp(Crud::PAGE_NEW,'Hier können Sie die Bilder mit der Gallery verbinden');
    }

    public function configureFields(string $pageName): iterable
    {

        $id = IdField::new('id');

        $file = TextField::new('file','Bild Auswahl')
        ->setFormType(ElFinderType::class)
        ->setFormTypeOptions(['instance' => 'user', 'enable' => true]);

        $type = TextField::new('type')->setFormTypeOption('disabled' ,true);
        $class = TextField::new('class')->setFormTypeOption('disabled' ,true);
        $user_id = TextField::new('user_id')->setFormTypeOption('disabled' ,true);
        $status = BooleanField::new('status');

        // output fields by page
        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $file, $type, $class, $user_id, $status];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $file, $type, $class, $user_id, $status];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$file, $type, $class, $user_id, $status];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$file, $type, $class, $user_id, $status];
        }
    }

    public function createEntity(string $entityFqcn)
    {
        $user = $this->userRepository->findOneBy(['email'=>$this->getUser()->getUsername()]);

        $media = new Media();
        $media->setUserId($user->getId());
        $media->setType('detail');
        $media->setClass('images');
        $media->setStatus(true);

        return $media;
    }


    public static function getEntityFqcn(): string
    {
        return self::$entityFqcn;
    }
}
