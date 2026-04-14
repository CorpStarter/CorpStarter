<?php

namespace App\DataFixtures;

use App\Entity\UserTypes;
use App\Entity\Users;
use App\Entity\Project;
use App\Entity\ProjectStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TestDataFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        // Créer les types d'utilisateurs
        $clientType = new UserTypes();
        $clientType->setName('Client');
        $clientType->setCreationDate(new \DateTime());
        $clientType->setCanAcceptProject(false);
        $manager->persist($clientType);

        $approverType = new UserTypes();
        $approverType->setName('Approbateur');
        $approverType->setCreationDate(new \DateTime());
        $approverType->setCanAcceptProject(true);
        $manager->persist($approverType);

        $manager->flush();

        // Créer les statuts de projet
        $statusPending = new ProjectStatus();
        $statusPending->setStatusName('En attente');
        $statusPending->setCreationDate(new \DateTime());
        $statusPending->setValidated(false);
        $manager->persist($statusPending);

        $statusApproved = new ProjectStatus();
        $statusApproved->setStatusName('Approuvé');
        $statusApproved->setCreationDate(new \DateTime());
        $statusApproved->setValidated(true);
        $manager->persist($statusApproved);

        $statusRejected = new ProjectStatus();
        $statusRejected->setStatusName('Rejeté');
        $statusRejected->setCreationDate(new \DateTime());
        $statusRejected->setValidated(false);
        $manager->persist($statusRejected);

        $manager->flush();

        // Créer un utilisateur client
        $userClient = new Users();
        $userClient->setUsername('testuser');
        $userClient->setEmail('test@test.com');
        $userClient->setFirstName('Test');
        $userClient->setLastName('User');
        $userClient->setEmailConfirmed(true);
        $userClient->setTermsAccepted(true);
        $userClient->setUserType($clientType);
        
        $hashedPasswordClient = $this->passwordHasher->hashPassword($userClient, 'password123');
        $userClient->setPasswordHash($hashedPasswordClient);
        $manager->persist($userClient);

        // Créer un utilisateur approbateur
        $userApprover = new Users();
        $userApprover->setUsername('approver');
        $userApprover->setEmail('approver@test.com');
        $userApprover->setFirstName('Approver');
        $userApprover->setLastName('Admin');
        $userApprover->setEmailConfirmed(true);
        $userApprover->setTermsAccepted(true);
        $userApprover->setUserType($approverType);
        
        $hashedPasswordApprover = $this->passwordHasher->hashPassword($userApprover, 'password123');
        $userApprover->setPasswordHash($hashedPasswordApprover);
        $manager->persist($userApprover);

        $manager->flush();

        // Créer des projets de test
        $project1 = new Project();
        $project1->setName('Projet Web E-commerce');
        $project1->setDescription('Développement d\'une plateforme e-commerce');
        $project1->setRequestedBudget(50000);
        $project1->setAllocatedBudget(0);
        $project1->setStatus($statusPending);
        $project1->setCreationDate(new \DateTime());
        $project1->addRequester($userClient);
        $manager->persist($project1);

        $project2 = new Project();
        $project2->setName('Application Mobile');
        $project2->setDescription('Création d\'une application mobile iOS/Android');
        $project2->setRequestedBudget(75000);
        $project2->setAllocatedBudget(65000);
        $project2->setStatus($statusApproved);
        $project2->setCreationDate(new \DateTime('-30 days'));
        $project2->setApprover($userApprover);
        $project2->addRequester($userClient);
        $manager->persist($project2);

        $project3 = new Project();
        $project3->setName('Refonte Site Corporate');
        $project3->setDescription('Refonte complète du site web existant');
        $project3->setRequestedBudget(30000);
        $project3->setAllocatedBudget(0);
        $project3->setStatus($statusRejected);
        $project3->setCreationDate(new \DateTime('-60 days'));
        $project3->addRequester($userClient);
        $manager->persist($project3);

        $manager->flush();
    }
}
