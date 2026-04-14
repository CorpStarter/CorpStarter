<?php

namespace App\Controller;

use App\Service\AuthService;
use App\Service\ProjectService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/projects')]
class ProjectController extends AbstractController
{
    public function __construct(
        private ProjectService $projectService,
        private AuthService $authService,
    ) {
    }

    private function getAuthenticatedUser(Request $request)
    {
        $token = null;

        // Try to get token from query string first (for GET requests)
        $token = $request->query->get('token');

        // If not in query string, try from JSON body (for POST/PUT requests)
        if (!$token && $request->getContent()) {
            $data = json_decode($request->getContent(), true);
            $token = $data['token'] ?? null;
        }

        if (!$token) {
            throw new UnauthorizedHttpException('Bearer', 'Not logged in - token required in body or query');
        }

        $user = $this->authService->validateToken($token);

        if (!$user) {
            throw new UnauthorizedHttpException('Bearer', 'Not logged in - invalid token');
        }

        return $user;
    }

    /**
     * Create a new project
     * 
     * @OA\Post(
     *     path="/projects",
     *     summary="Create a new project",
     *     tags={"Projects"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","requested_budget","illustration_path"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="requested_budget", type="string"),
     *             @OA\Property(property="illustration_path", type="string")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Project created"),
     *     @OA\Response(response=400, description="Missing project name"),
     *     @OA\Response(response=401, description="Not logged in")
     * )
     */
    #[Route('', name: 'app_project_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        try {
            $user = $this->getAuthenticatedUser($request);
            $data = json_decode($request->getContent(), true);

            $result = $this->projectService->createProject(
                $data['name'] ?? '',
                $data['requested_budget'] ?? '',
                $data['illustration_path'] ?? '',
                $data['description'] ?? '',
                $user
            );

            return $this->json($result, 201);
        } catch (\Exception $e) {
            $statusCode = ($e instanceof HttpException) ? $e->getStatusCode() : 500;
            return $this->json(['error' => $e->getMessage()], $statusCode);
        }
    }

    /**
     * List all projects with optional filters
     * 
     * @OA\Get(
     *     path="/projects",
     *     summary="Get all projects",
     *     tags={"Projects"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="status", in="query", type="string", description="Filter by status name"),
     *     @OA\Parameter(name="name", in="query", type="string", description="Filter by project name (like)"),
     *     @OA\Parameter(name="created_before", in="query", type="string", description="Filter by creation date before"),
     *     @OA\Parameter(name="created_after", in="query", type="string", description="Filter by creation date after"),
     *     @OA\Parameter(name="requester_name", in="query", type="string", description="Filter by requester name (like)"),
     *     @OA\Parameter(name="approver_name", in="query", type="string", description="Filter by approver name (like)"),
     *     @OA\Response(response=200, description="List of projects"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    #[Route('', name: 'app_project_list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        try {
            $this->getAuthenticatedUser($request); // Just to check authentication, we don't need the user object here

            $result = $this->projectService->getProjects(
                $request->query->get('status'),
                $request->query->get('name'),
                $request->query->get('created_before'),
                $request->query->get('created_after'),
                $request->query->get('requester_name'),
                $request->query->get('approver_name')
            );

            return $this->json($result, 200);
        } catch (\Exception $e) {
            $statusCode = ($e instanceof HttpException) ? $e->getStatusCode() : 500;
            return $this->json(['error' => $e->getMessage()], $statusCode);
        }
    }

    /**
     * Update an existing project
     * 
     * @OA\Put(
     *     path="/projects/{id}",
     *     summary="Update a project",
     *     tags={"Projects"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, type="integer"),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="requested_budget", type="string"),
     *             @OA\Property(property="illustration_path", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Project updated"),
     *     @OA\Response(response=403, description="Not the owner"),
     *     @OA\Response(response=422, description="Project already validated")
     * )
     */
    #[Route('/{id}', name: 'app_project_update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        try {
            $user = $this->getAuthenticatedUser($request);
            $data = json_decode($request->getContent(), true);

            $result = $this->projectService->updateProject(
                $id,
                $user,
                $data['name'] ?? null,
                $data['requested_budget'] ?? null,
                $data['illustration_path'] ?? null,
                $data['description'] ?? null
            );

            return $this->json($result, 200);
        } catch (\Exception $e) {
            $statusCode = ($e instanceof HttpException) ? $e->getStatusCode() : 500;
            return $this->json(['error' => $e->getMessage()], $statusCode);
        }
    }

    /**
     * Delete a project
     * 
     * @OA\Delete(
     *     path="/projects/{id}",
     *     summary="Delete a project",
     *     tags={"Projects"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, type="integer"),
     *     @OA\Response(response=204, description="Project deleted"),
     *     @OA\Response(response=403, description="Not the owner"),
     *     @OA\Response(response=422, description="Project already validated")
     * )
     */
    #[Route('/{id}', name: 'app_project_delete', methods: ['DELETE'])]
    public function delete(int $id, Request $request): JsonResponse
    {
        try {
            $user = $this->getAuthenticatedUser($request);
            $this->projectService->deleteProject($id, $user);
            return $this->json(null, 204);
        } catch (\Exception $e) {
            $statusCode = ($e instanceof HttpException) ? $e->getStatusCode() : 500;
            return $this->json(['error' => $e->getMessage()], $statusCode);
        }
    }

    /**
     * Join a project as attendee
     * 
     * @OA\Post(
     *     path="/projects/{id}/join",
     *     summary="Join a project",
     *     tags={"Projects"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, type="integer"),
     *     @OA\Response(response=200, description="Joined successfully"),
     *     @OA\Response(response=404, description="Project not found"),
     *     @OA\Response(response=409, description="Already joined")
     * )
     */
    #[Route('/{id}/join', name: 'app_project_join', methods: ['POST'])]
    public function join(int $id, Request $request): JsonResponse
    {
        try {
            $user = $this->getAuthenticatedUser($request);
            $result = $this->projectService->joinProject($id, $user);
            return $this->json($result, 200);
        } catch (\Exception $e) {
            $statusCode = ($e instanceof HttpException) ? $e->getStatusCode() : 500;
            return $this->json(['error' => $e->getMessage()], $statusCode);
        }
    }

    #[Route('/{id}/leave', name: 'app_project_leave', methods: ['POST'])]
    public function leave(int $id, Request $request): JsonResponse
    {
        try {
            $user = $this->getAuthenticatedUser($request);
            $result = $this->projectService->leaveProject($id, $user);
            return $this->json($result, 200);
        } catch (\Exception $e) {
            $statusCode = ($e instanceof HttpException) ? $e->getStatusCode() : 500;
            return $this->json(['error' => $e->getMessage()], $statusCode);
        }
    }

    #[Route('/{id}/joined-users', name: 'app_project_get_joined_users', methods: ['GET'])]
    public function getJoinedUsers(int $id, Request $request): JsonResponse
    {
        try {
            $this->getAuthenticatedUser($request); // Just to check authentication
            $result = $this->projectService->getJoinedUsers($id);
            return $this->json($result, 200);
        } catch (\Exception $e) {
            $statusCode = ($e instanceof HttpException) ? $e->getStatusCode() : 500;
            return $this->json(['error' => $e->getMessage()], $statusCode);
        }
    }

    #[Route('/status', name: 'app_project_get_status', methods: ['GET'])]
    public function getStatus(Request $request): JsonResponse
    {
        try {
            $this->getAuthenticatedUser($request); // Just to check authentication
            $result = $this->projectService->getAllStatuses();
            return $this->json($result, 200);
        } catch (\Exception $e) {
            $statusCode = ($e instanceof HttpException) ? $e->getStatusCode() : 500;
            return $this->json(['error' => $e->getMessage()], $statusCode);
        }
    }
}
