<?php

namespace App\Controller\Admin;

use App\Entity\Users;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField; // <--- Importé correctement ici

class UsersCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Users::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('username', 'Nom d\'utilisateur'),
            TextField::new('first_name', 'Prénom'),
            TextField::new('last_name', 'Nom'),
            EmailField::new('email', 'Email'),
            
            // Le champ qui posait problème
            AssociationField::new('user_type', 'Type de compte'),

            TextField::new('password_hash', 'Mot de passe (Brut)')
                ->onlyOnForms(),

            BooleanField::new('email_confirmed', 'Email vérifié'),
            BooleanField::new('terms_accepted', 'CGU acceptées'),
            
            DateTimeField::new('creation_date', 'Date d\'inscription')
                ->hideOnForm(),
        ];
    }
}