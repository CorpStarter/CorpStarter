<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

// ✅ Nouvelle norme EasyAdmin 5 pour définir la route
#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    /**
     * Cette méthode définit ce qui s'affiche sur la page d'accueil de l'admin.
     */
    public function index(): Response
    {
        // On affiche le layout de base pour voir ton menu et ton titre immédiatement
        return $this->render('@EasyAdmin/page/content.html.twig');
    }

    /**
     * Configuration visuelle du Dashboard (Haut de page).
     */
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('<b>Corp</b>Starter <small>v1.0</small>')
            // Note : Le mode sombre est activé par défaut dans cette version.
            ->setFaviconPath('favicon.ico');
    }

    /**
     * Configuration du menu latéral (Sidebar).
     */
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Tableau de bord', 'fa fa-home');

        yield MenuItem::section('L\'Entreprise');
        // 🛑 Les lignes ci-dessous sont en commentaire car elles nécessitent 
        // que ton collègue (Lead Backend) ait créé les entités User et Project.
        // yield MenuItem::linkToCrud('Collaborateurs', 'fas fa-users', User::class);
        
        yield MenuItem::section('Innovation');
        // yield MenuItem::linkToCrud('Projets soumis', 'fas fa-lightbulb', Project::class);

        yield MenuItem::section('Paramètres');
        // 🛑 On commente le logout tant que le système de sécurité (firewall) 
        // n'est pas configuré par le backend pour éviter l'erreur 500.
        // yield MenuItem::linkToLogout('Déconnexion', 'fa fa-sign-out');
    }
}