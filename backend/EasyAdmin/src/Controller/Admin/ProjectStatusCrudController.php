<?php

namespace App\Controller\Admin;

use App\Entity\ProjectStatus;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProjectStatusCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProjectStatus::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            // On utilise 'status_name' car c'est le nom dans ton entité !
            TextField::new('status_name', 'Nom du statut'), 
        ];
    }
}