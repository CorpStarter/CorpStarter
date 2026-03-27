<?php

namespace App\DataFixtures;

use App\Entity\UserTypes;
use App\Entity\ProjectStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // --- 1. Insertion des UserTypes ---
        $adminType = new UserTypes();
        $adminType->setName("Admin")
                  ->setCanAcceptProject(true);
        $manager->persist($adminType);

        $userType = new UserTypes();
        $userType->setName("User")
                 ->setCanAcceptProject(false);
        $manager->persist($userType);

        // --- 2. Insertion des ProjectStatus ---
        $pending = new ProjectStatus();
        $pending->setStatusName("Pending")
                ->setValidated(false);
        $manager->persist($pending);

        $approved = new ProjectStatus();
        $approved->setStatusName("Approved")
                 ->setValidated(true);
        $manager->persist($approved);

        $rejected = new ProjectStatus();
        $rejected->setStatusName("Rejected")
                 ->setValidated(false);
        $manager->persist($rejected);

        // On envoie tout en base !
        $manager->flush();
    }
}