<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Users;
use App\Repository\ProjectRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/api/projects', name: 'api_projects_')]
class ProjectController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ProjectRepository $projectRepository,
        private UsersRepository $usersRepository
    ) {}

    #[Route('', name: 'list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
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

            $userId = $request->query->get('user_id');
            if ($userId) {
                $projects = $this->projectRepository->findBy(['requester' => $userId]);
            } else {
                $projects = $this->projectRepository->findAll();
            }

            $projectsData = [];
            foreach ($projects as $project) {
                $projectsData[] = [
                    'id' => $project->getId(),
                    'name' => $project->getName(),
                    'requested_budget' => $project->getRequestedBudget(),
                    'allocated_budget' => $project->getAllocatedBudget(),
                    'creation_date' => $project->getCreationDate()->format('Y-m-d H:i:s'),
                    'status' => $project->getStatus()?->getStatusName(),
                    'illustration_path' => $project->getIllustrationPath()
                ];
            }

            return new JsonResponse(['projects' => $projectsData]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
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

            if (!isset($data['name'], $data['user_id'])) {
                return new JsonResponse(['error' => 'Name and user_id required'], 400);
            }

            $user = $this->usersRepository->find($data['user_id']);
            if (!$user) {
                return new JsonResponse(['error' => 'User not found'], 404);
            }

            // Vérifier que l'utilisateur a le droit de créer un projet
            $userType = $user->getUserType();
            if (!$userType || !$userType->isCanAcceptProject()) {
                return new JsonResponse(['error' => 'User not authorized to create projects'], 403);
            }

            $project = new Project();
            $project->setName($data['name']);
            $project->setRequestedBudget($data['requested_budget'] ?? null);
            $project->setIllustrationPath($data['illustration_path'] ?? null);
            $project->setCreationDate(new \DateTime());
            $project->addRequester($user);

            $this->entityManager->persist($project);
            $this->entityManager->flush();

            return new JsonResponse([
                'message' => 'Project created successfully',
                'project' => [
                    'id' => $project->getId(),
                    'name' => $project->getName(),
                    'requested_budget' => $project->getRequestedBudget(),
                    'creation_date' => $project->getCreationDate()->format('Y-m-d H:i:s')
                ]
            ], 201);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/{id}', name: 'edit', methods: ['PUT', 'PATCH'])]
    public function edit(int $id, Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $userId = $data['user_id'] ?? null;

            if (!$userId) {
                return new JsonResponse(['error' => 'user_id required'], 400);
            }

            $token = $request->query->get('token');
            if (!$token) {
                return new JsonResponse(['error' => 'Token required'], 400);
            }

            $user = $this->usersRepository->findOneBy(['conection_token' => $token]);
            if (!$user || !$user->getTokenDate() || (new \DateTime())->diff($user->getTokenDate())->i > 30) {
                return new JsonResponse(['error' => 'Invalid or expired token'], 401);
            }

            $project = $this->projectRepository->find($id);
            if (!$project) {
                return new JsonResponse(['error' => 'Project not found'], 404);
            }

            $user = $this->usersRepository->find($userId);
            if (!$user) {
                return new JsonResponse(['error' => 'User not found'], 404);
            }

            // Vérifier que l'utilisateur est propriétaire du projet
            if (!$project->getRequester()->contains($user)) {
                return new JsonResponse(['error' => 'Unauthorized'], 403);
            }

            // Vérifier que le projet n'est pas validé
            if ($project->getStatus() && $project->getStatus()->getId() !== 1) {
                return new JsonResponse(['error' => 'Cannot edit validated project'], 403);
            }

            if (isset($data['name'])) {
                $project->setName($data['name']);
            }
            if (isset($data['requested_budget'])) {
                $project->setRequestedBudget($data['requested_budget']);
            }
            if (isset($data['illustration_path'])) {
                $project->setIllustrationPath($data['illustration_path']);
            }

            $this->entityManager->flush();

            return new JsonResponse([
                'message' => 'Project updated successfully',
                'project' => [
                    'id' => $project->getId(),
                    'name' => $project->getName(),
                    'requested_budget' => $project->getRequestedBudget()
                ]
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id, Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $userId = $data['user_id'] ?? null;

            if (!$userId) {
                return new JsonResponse(['error' => 'user_id required'], 400);
            }

            $token = $request->query->get('token');
            if (!$token) {
                return new JsonResponse(['error' => 'Token required'], 400);
            }

            $user = $this->usersRepository->findOneBy(['conection_token' => $token]);
            if (!$user || !$user->getTokenDate() || (new \DateTime())->diff($user->getTokenDate())->i > 30) {
                return new JsonResponse(['error' => 'Invalid or expired token'], 401);
            }

            $project = $this->projectRepository->find($id);
            if (!$project) {
                return new JsonResponse(['error' => 'Project not found'], 404);
            }

            $user = $this->usersRepository->find($userId);
            if (!$user) {
                return new JsonResponse(['error' => 'User not found'], 404);
            }

            // Vérifier que l'utilisateur est propriétaire du projet
            if (!$project->getRequester()->contains($user)) {
                return new JsonResponse(['error' => 'Unauthorized'], 403);
            }

            // Vérifier que le projet n'est pas validé
            if ($project->getStatus() && $project->getStatus()->getId() !== 1) {
                return new JsonResponse(['error' => 'Cannot delete validated project'], 403);
            }

            $this->entityManager->remove($project);
            $this->entityManager->flush();

            return new JsonResponse(['message' => 'Project deleted successfully']);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/{id}/join', name: 'join', methods: ['POST'])]
    public function join(int $id, Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (!isset($data['user_id'])) {
                return new JsonResponse(['error' => 'user_id required'], 400);
            }

            $token = $request->query->get('token');
            if (!$token) {
                return new JsonResponse(['error' => 'Token required'], 400);
            }

            $user = $this->usersRepository->findOneBy(['conection_token' => $token]);
            if (!$user || !$user->getTokenDate() || (new \DateTime())->diff($user->getTokenDate())->i > 30) {
                return new JsonResponse(['error' => 'Invalid or expired token'], 401);
            }

            $project = $this->projectRepository->find($id);
            if (!$project) {
                return new JsonResponse(['error' => 'Project not found'], 404);
            }

            $user = $this->usersRepository->find($data['user_id']);
            if (!$user) {
                return new JsonResponse(['error' => 'User not found'], 404);
            }

            // Vérifier que l'utilisateur n'est pas déjà dans le projet
            if ($project->getRequester()->contains($user)) {
                return new JsonResponse(['error' => 'User already in project'], 409);
            }

            $project->addRequester($user);
            $this->entityManager->flush();

            return new JsonResponse([
                'message' => 'Successfully joined project',
                'project' => [
                    'id' => $project->getId(),
                    'name' => $project->getName()
                ]
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
}
