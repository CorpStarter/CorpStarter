<?php

namespace App\Service;

use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Workflow\WorkflowInterface;

class ProjectService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private WorkflowInterface $projectPublishingStateMachine
    ) {
    }

    public function checkFundingStatus(Project $project): void
    {
        // Si le montant actuel atteint ou dépasse l'objectif fixé
        if ($project->getCurrentAmount() >= $project->getGoalAmount()) {
            
            // On vérifie qu'on a le droit de passer à l'état "funded" (depuis "published")
            if ($this->projectPublishingStateMachine->can($project, 'mark_as_funded')) {
                // Application de la transition
                $this->projectPublishingStateMachine->apply($project, 'mark_as_funded');
                
                // Sauvegarde en base de données du nouveau statut
                $this->entityManager->flush();
            }
        }
    }
}
