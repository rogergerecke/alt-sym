<?php

namespace App\Controller\Admin;

use App\Entity\Advertising;
use App\Entity\Hostel;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use FM\ElfinderBundle\Form\Type\ElFinderType;
use Symfony\Component\DomCrawler\Field\FileFormField;

class AdvertisingCrudController extends AbstractCrudController
{
    public static $entityFqcn = Advertising::class;

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->addFormTheme('@FMElfinderBundle/Form/elfinder_widget.html.twig')
            ->setPageTitle(Crud::PAGE_EDIT, 'Banner Werbung')
            ->setHelp(Crud::PAGE_EDIT, 'Werbebanner bearbeiten');

    }


    public function configureFields(string $pageName): iterable
    {

        $id = IdField::new('id');
        $title = TextField::new('title');
        $text = TextEditorField::new('text');
        $link = UrlField::new('link');

        $image = TextField::new('image')
            ->setFormType(ElFinderType::class)
            ->setFormTypeOptions(['instance' => 'banner', 'enable' => true]);

        $status = BooleanField::new('status');
        $start_date = DateField::new('start_date_advertising');
        $end_date = DateField::new('end_date_advertising');

        switch ($pageName) {
            case Crud::PAGE_INDEX:
            case Crud::PAGE_DETAIL:
                return [$id, $title, $text, $link, $image, $status, $start_date, $end_date];
                break;
            case Crud::PAGE_EDIT:
            case Crud::PAGE_NEW:
                return [$title, $text, $link, $image, $status, $start_date, $end_date];
                break;
        }
    }


    /**
     * Create a new advertising with
     * the id from the logged in user
     * a user cant have many advertising's
     *
     * @param string $entityFqcn
     * @return Advertising|mixed
     */
    public function createEntity(string $entityFqcn)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $advertising = new Advertising();
        $advertising->setUserId((int)$user->getId());

        return $advertising;
    }


    public static function getEntityFqcn(): string
    {
        return self::$entityFqcn;
    }


}
