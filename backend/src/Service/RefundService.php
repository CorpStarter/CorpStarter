<?php

namespace App\Service;

use App\Entity\Project;
use Psr\Log\LoggerInterface;

class RefundService
{
    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    public function refundInvestors(Project $project): void
    {
        // Simulation pour le rôle non-fini de l'Étudiant 1 : 
        // L'entité Transaction et Wallet n'existant pas encore, on simule le remboursement par des logs.
        
        $this->logger->info(sprintf(
            '[REFUND] Début du remboursement pour le projet ID #%d',
            $project->getId()
        ));
        
        $this->logger->info(sprintf(
            '[REFUND] %.2f crédits doivent être remboursés aux investisseurs.',
            $project->getCurrentAmount()
        ));

        // TODO: Quand Étudiant 1 terminera son rôle, implémenter la logique suivante :
        // 1. Récupérer la liste des transactions liées à ce $project
        // 2. Parcourir chaque transaction :
        //      $wallet = $transaction->getUser()->getWallet();
        //      $wallet->setBalance($wallet->getBalance() + $transaction->getAmount());
        // 3. Persister l'ensemble de l'argent re-crédité avec EntityManager->flush()
    }
}
