<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\ProjectStatus;
use App\Repository\ProjectRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/api/admin', name: 'api_admin_')]
class AdminController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ProjectRepository $projectRepository,
        private UsersRepository $usersRepository
    ) {}

    #[Route('/projects/{id}/status', name: 'change_project_status', methods: ['PATCH'])]
    public function changeProjectStatus(int $id, Request $request): JsonResponse
    {
        try {
            $token = $request->query->get('token');
            if (!$token) {
                return new JsonResponse(['error' => 'Token required'], 400);
            }

            $user = $this->usersRepository->findOneBy(['conection_token' => $token]);
            if (!$user || !$user->getTokenDate() || (new \DateTime())->diff($user->getTokenDate())->i > 30) {
                return new JsonResponse(['error' => 'Invalid or expired token'], 401);
            }

            $data = json_decode($request->getContent(), true);

            if (!isset($data['admin_id'], $data['status_id'])) {
                return new JsonResponse(['error' => 'admin_id and status_id required'], 400);
            }

            $admin = $this->usersRepository->find($data['admin_id']);
            if (!$admin) {
                return new JsonResponse(['error' => 'Admin not found'], 404);
            }

            $userType = $admin->getUserType();
            if (!$userType || $userType->getName() !== 'Admin') {
                return new JsonResponse(['error' => 'Unauthorized: Admin access required'], 403);
            }

            $project = $this->projectRepository->find($id);
            if (!$project) {
                return new JsonResponse(['error' => 'Project not found'], 404);
            }

            $status = $this->entityManager->getRepository(ProjectStatus::class)->find($data['status_id']);
            if (!$status) {
                return new JsonResponse(['error' => 'Status not found'], 404);
            }

            $project->setStatus($status);
            $this->entityManager->flush();

            return new JsonResponse([
                'message' => 'Project status updated successfully',
                'project' => [
                    'id' => $project->getId(),
                    'name' => $project->getName(),
                    'status' => $project->getStatus()->getStatusName()
                ]
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/projects/{id}/budget', name: 'allocate_budget', methods: ['PATCH'])]
    public function allocateBudget(int $id, Request $request): JsonResponse
    {
        try {
            $token = $request->query->get('token');
            if (!$token) {
                return new JsonResponse(['error' => 'Token required'], 400);
            }

            $user = $this->usersRepository->findOneBy(['conection_token' => $token]);
            if (!$user || !$user->getTokenDate() || (new \DateTime())->diff($user->getTokenDate())->i > 30) {
                return new JsonResponse(['error' => 'Invalid or expired token'], 401);
            }

            $data = json_decode($request->getContent(), true);

            if (!isset($data['admin_id'], $data['allocated_budget'])) {
                return new JsonResponse(['error' => 'admin_id and allocated_budget required'], 400);
            }

            $admin = $this->usersRepository->find($data['admin_id']);
            if (!$admin) {
                return new JsonResponse(['error' => 'Admin not found'], 404);
            }

            $userType = $admin->getUserType();
            if (!$userType || $userType->getName() !== 'Admin') {
                return new JsonResponse(['error' => 'Unauthorized: Admin access required'], 403);
            }

            $project = $this->projectRepository->find($id);
            if (!$project) {
                return new JsonResponse(['error' => 'Project not found'], 404);
            }

            $allocatedBudget = (float) $data['allocated_budget'];
            $requestedBudget = (float) $project->getRequestedBudget();

            if ($allocatedBudget > $requestedBudget) {
                return new JsonResponse(['error' => 'Allocated budget cannot exceed requested budget'], 400);
            }

            $project->setAllocatedBudget((string) $allocatedBudget);
            $this->entityManager->flush();

            return new JsonResponse([
                'message' => 'Budget allocated successfully',
                'project' => [
                    'id' => $project->getId(),
                    'name' => $project->getName(),
                    'requested_budget' => $project->getRequestedBudget(),
                    'allocated_budget' => $project->getAllocatedBudget()
                ]
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
}
