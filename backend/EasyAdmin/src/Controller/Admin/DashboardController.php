<?php

namespace App\Controller\Admin;

use App\Controller\Admin\UsersCrudController;
use App\Controller\Admin\ProjectCrudController;
use App\Controller\Admin\UserTypesCrudController; 
use App\Controller\Admin\ProjectStatusCrudController; 
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator; // Import nécessaire
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        // FORCE LA REDIRECTION : On n'affiche plus la page Welcome, 
        // on va direct aux utilisateurs.
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(UsersCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('<b>Corp</b>Starter');
    }

 
 public function configureMenuItems(): iterable
{
    yield MenuItem::linkToDashboard('Accueil', 'fa fa-home');

    // Section Utilisateurs
    yield MenuItem::section('Membres');
    yield MenuItem::linkTo(UsersCrudController::class, 'Liste des utilisateurs', 'fas fa-users');
    yield MenuItem::linkTo(UserTypesCrudController::class, 'Types de comptes', 'fas fa-user-tag');

    // Section Projets
    yield MenuItem::section('Gestion Projets');
    yield MenuItem::linkTo(ProjectStatusCrudController::class, 'Statuts des projets', 'fas fa-tasks');
    yield MenuItem::linkTo(ProjectCrudController::class, 'Tous les projets', 'fas fa-briefcase');
}
}