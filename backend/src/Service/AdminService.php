<?php

namespace App\Service;

use App\Entity\Users;
use App\Repository\ProjectRepository;
use App\Repository\ProjectStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminService
{
    public function __construct(
        private ProjectRepository $projectRepository,
        private ProjectStatusRepository $projectStatusRepository,
        private EntityManagerInterface $entityManager,
    ) {}

    public function updateProjectAdmin(
        int $projectId,
        Users $admin,
        ?string $status = null,
        ?string $allocatedBudget = null
    ): array {
        // Check if user is admin
        if (!$admin->getUserType() || !$admin->getUserType()->isCanAcceptProject()) {
            throw new HttpException(403, 'Not Admin');
        }

        $project = $this->projectRepository->find($projectId);

        if (!$project) {
            throw new NotFoundHttpException('Project not found');
        }

        // Validate allocated budget
        if ($allocatedBudget !== null) {
            if (!is_numeric($allocatedBudget) || (float)$allocatedBudget < 0) {
                throw new BadRequestHttpException('Budget must be a positive number');
            }
            $project->setAllocatedBudget($allocatedBudget);
        }

        // Update status
        if ($status) {
            $projectStatus = $this->projectStatusRepository->findOneBy(['status_name' => $status]);
            if (!$projectStatus) {
                throw new BadRequestHttpException('Status ID invalid');
            }
            $project->setStatus($projectStatus);
            $project->setApprover($admin);
        }

        $this->entityManager->flush();

        return ['message' => 'Project updated successfully'];
    }
}
