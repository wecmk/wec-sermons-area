<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Entity\Series;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
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
            // the name visible to end users
            ->setTitle('WEC Members Area Admin')

            // the path defined in this method is passed to the Twig asset() function
            //->setFaviconPath('favicon.svg')

            // the domain used by default is 'messages'
            ->setTranslationDomain('admin')

            // there's no need to define the "text direction" explicitly because
            // its default value is inferred dynamically from the user locale
            ->setTextDirection('ltr')

            // set this option if you prefer the page content to span the entire
            // browser width, instead of the default design which sets a max width
            ->renderContentMaximized();

    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),

            MenuItem::section('Events'),
            MenuItem::linkToCrud('Events', 'fa fa-tags', Event::class),
            MenuItem::linkToCrud('Series', 'fa fa-cubes', Series::class),

            MenuItem::section('Manage Users'),
            MenuItem::linkToCrud('Users', 'fa  fa-user', User::class),
            MenuItem::section()->setPermission('CAN_SWITCH_USER'),
            MenuItem::linkToExitImpersonation('Stop impersonation', 'fa fa-exit')->setPermission('ROLE_ALLOWED_TO_SWITCH')->setPermission('CAN_SWITCH_USER'),

            MenuItem::section(),
            MenuItem::linkToLogout('Logout', 'fa fa-exit'),
        ];
    }
}