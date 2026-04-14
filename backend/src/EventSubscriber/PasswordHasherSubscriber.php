<?php

namespace App\EventSubscriber;

use App\Entity\Users;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordHasherSubscriber implements EventSubscriberInterface
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            // On écoute la création et la mise à jour
            BeforeEntityPersistedEvent::class => ['hashPassword'],
            BeforeEntityUpdatedEvent::class => ['hashPassword'],
        ];
    }

    public function hashPassword($event): void
    {
        $entity = $event->getEntityInstance();
        
        // On ne traite que l'entité Users
        if (!($entity instanceof Users)) {
            return;
        }

        $plainPassword = $entity->getPasswordHash();

        // 1. Si le champ est vide, on ne fait rien
        if (empty($plainPassword)) {
            return;
        }

        // 2. SÉCURITÉ EDIT : Si le mot de passe commence par $2y$, 
        // c'est qu'il est déjà hashé, on ne le re-hashe pas !
        if (str_starts_with($plainPassword, '$2y$')) {
            return;
        }

        // 3. Sinon, on hashe le nouveau mot de passe
        $hashed = $this->passwordHasher->hashPassword($entity, $plainPassword);
        $entity->setPasswordHash($hashed);
    }
}