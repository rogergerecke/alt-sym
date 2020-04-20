<?php

namespace App\Controller\Admin;

use App\Entity\HostelTypes;
use App\Entity\Regions;
use App\Entity\StaticSite;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('<strong>Altmühlsee</strong>');
    }

    public function configureCrud(): Crud
    {
        return Crud::new()
            ->setDateFormat('ddMMyyyy');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Seiten', 'fa fa-folder-open', StaticSite::class);
        yield MenuItem::linkToCrud('Benutzer', 'fa fa-folder-open', User::class);
        yield MenuItem::linkToCrud('Regionen', 'fa fa-folder-open', Regions::class);
        yield MenuItem::linkToCrud('Hostel Typen', 'fa fa-folder-open', HostelTypes::class);
    }
}
