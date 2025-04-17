<?php

namespace App\Controller\Admin;

use App\Entity\Job;
use App\Entity\Company;
use App\Entity\Category;
use App\Entity\Contact;
use App\Entity\User;
use App\Entity\JobApplication;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig'); 
        
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Joby');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Back to website', 'fa fa-home', 'app_home');
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-user');
        yield MenuItem::linkToCrud('Users', 'fa fa-users', User::class);
        yield MenuItem::linkToCrud('Jobs', 'fa fa-briefcase', Job::class);
        yield MenuItem::linkToCrud('Companies', 'fa fa-building', Company::class);
        yield MenuItem::linkToCrud('Categories', 'fa fa-tags', Category::class);
        yield MenuItem::linkToCrud('Job Applications', 'fa fa-file', JobApplication::class);
        yield MenuItem::linkToCrud('Contacts', 'fa fa-phone', Contact::class);
        yield MenuItem::section('User Management');
    }
}
