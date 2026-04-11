<?php

namespace App\Controller;

use App\Service\AdminService;
use App\Service\AuthService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/projects')]
class AdminController extends AbstractController
{
    public function __construct(
        private AdminService $adminService,
        private AuthService $authService,
    ) {}

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
     * Update project status and/or budget (Admin only)
     * 
     * @OA\Patch(
     *     path="/admin/projects/{id}",
     *     summary="Update project status and budget",
     *     tags={"Admin Projects"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, type="integer"),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", description="New status name"),
     *             @OA\Property(property="allocated_budget", type="string", description="Budget to allocate")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Project updated"),
     *     @OA\Response(response=400, description="Budget < 0 or Status invalid"),
     *     @OA\Response(response=403, description="Forbidden/Not Admin"),
     *     @OA\Response(response=404, description="Status ID invalid or Project not found")
     * )
     */
    #[Route('/{id}', name: 'app_admin_projects_update', methods: ['PATCH'])]
    public function update(int $id, Request $request): JsonResponse
    {
        try {
            $user = $this->getAuthenticatedUser($request);
            $data = json_decode($request->getContent(), true);

            $result = $this->adminService->updateProjectAdmin(
                $id,
                $user,
                $data['status'] ?? null,
                $data['allocated_budget'] ?? null
            );

            return $this->json($result, 200);
        } catch (\Exception $e) {
            $statusCode = ($e instanceof HttpException) ? $e->getStatusCode() : 500;
            return $this->json(['error' => $e->getMessage()], $statusCode);
        }
    }
}
