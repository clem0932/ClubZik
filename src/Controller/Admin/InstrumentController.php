<?php

namespace App\Controller\Admin;

use App\Entity\Instrument;
use App\Entity\Local;
use App\Entity\Owner;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InstrumentController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        // redirect to some CRUD controller
        $routeBuilder = $this->container->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(InstrumentCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new ()
            ->setTitle('ClubZik');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Instruments', 'fas fa-list', Instrument::class);
        yield MenuItem::linkToCrud('Locations', 'fas fa-list', Local::class);
        yield MenuItem::linkToCrud('Owners', 'fas fa-list', Owner::class);
        yield MenuItem::linkToCrud('Users', 'fas fa-list', User::class);
    }
}
