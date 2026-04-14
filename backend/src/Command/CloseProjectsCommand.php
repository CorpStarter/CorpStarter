<?php

namespace App\Command;

use App\Repository\ProjectRepository;
use App\Service\RefundService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Workflow\WorkflowInterface;

#[AsCommand(
    name: 'app:close-projects',
    description: 'Ferme les projets expirés et déclenche les remboursements',
)]
class CloseProjectsCommand extends Command
{
    public function __construct(
        private ProjectRepository $projectRepository,
        private WorkflowInterface $projectPublishingStateMachine,
        private RefundService $refundService,
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Fermeture automatique des projets expirés');

        $now = new \DateTimeImmutable();
        $expiredProjects = $this->projectRepository->findExpiredProjects($now);

        if (count($expiredProjects) === 0) {
            $io->success('Aucun projet expiré trouvé.');
            return Command::SUCCESS;
        }

        foreach ($expiredProjects as $project) {
            // Uniquement si on a le droit de passer ce projet en expired
            if ($this->projectPublishingStateMachine->can($project, 'expire')) {
                $this->projectPublishingStateMachine->apply($project, 'expire');
                
                // Remboursement des investisseurs
                $this->refundService->refundInvestors($project);
            }
        }

        $this->entityManager->flush();

        $io->success(sprintf('%d projet(s) ont été expiré(s) et les remboursements déclenchés.', count($expiredProjects)));

        return Command::SUCCESS;
    }
}
