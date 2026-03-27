<?php

namespace App\Controller\Admin;

use App\Entity\UserTypes;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserTypesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UserTypes::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            // Vérifie dans ton entité si le champ s'appelle 'name', 'label' ou 'title'
            TextField::new('name', 'Nom du type d\'utilisateur'),
        ];
    }
}