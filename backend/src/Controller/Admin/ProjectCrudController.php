<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField; // <-- Import vital

class ProjectCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Project::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'Nom du projet'),
            NumberField::new('requested_budget', 'Budget demandé'),
            NumberField::new('allocated_budget', 'Budget alloué'),
            TextField::new('illustration_path', 'Chemin de l\'image'),
            DateTimeField::new('creation_date', 'Date de création'),
            
            // On ajoute enfin le champ qui lie le projet à son statut
            AssociationField::new('status', 'Statut du projet'),
        ];
    }
}