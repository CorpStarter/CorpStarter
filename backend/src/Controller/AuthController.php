<?php

namespace App\Controller;

use App\Service\AuthService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/auth')]
class AuthController extends AbstractController
{
    public function __construct(
        private AuthService $authService,
    ) {}

    /**
     * Register a new user
     * 
     * @OA\Post(
     *     path="/auth/register",
     *     summary="Register a new user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="User registration data",
     *         @OA\JsonContent(
     *             required={"username","last_name","first_name","password","email","user_type"},
     *             @OA\Property(property="username", type="string"),
     *             @OA\Property(property="last_name", type="string"),
     *             @OA\Property(property="first_name", type="string"),
     *             @OA\Property(property="password", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="user_type", type="string", description="User or Admin")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="token", type="string"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Invalid email"),
     *     @OA\Response(response=409, description="Email already exists")
     * )
     */
    #[Route('/register', name: 'app_auth_register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            $result = $this->authService->register(
                $data['username'] ?? '',
                $data['last_name'] ?? '',
                $data['first_name'] ?? '',
                $data['password'] ?? '',
                $data['email'] ?? '',
                $data['user_type'] ?? ''
            );

            return $this->json($result, 201);
        } catch (\Exception $e) {
            $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 400;
            return $this->json(['error' => $e->getMessage()], $statusCode);
        }
    }

    /**
     * Verify email with token
     * 
     * @OA\Patch(
     *     path="/auth/verify-email",
     *     summary="Verify user email",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"token"},
     *             @OA\Property(property="token", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Email verified successfully"
     *     ),
     *     @OA\Response(response=400, description="Invalid token"),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    #[Route('/verify-email', name: 'app_auth_verify_email', methods: ['PATCH'])]
    public function verifyEmail(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            $result = $this->authService->verifyEmail($data['token'] ?? '');
            return $this->json($result, 200);
        } catch (\Exception $e) {
            $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 400;
            return $this->json(['error' => $e->getMessage()], $statusCode);
        }
    }

    /**
     * Login user
     * 
     * @OA\Post(
     *     path="/auth/login",
     *     summary="Login user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string"),
     *             @OA\Property(property="user_id", type="integer"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Wrong credentials")
     * )
     */
    #[Route('/login', name: 'app_auth_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            $result = $this->authService->login(
                $data['email'] ?? '',
                $data['password'] ?? ''
            );

            return $this->json($result, 200);
        } catch (\Exception $e) {
            $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 400;
            return $this->json(['error' => $e->getMessage()], $statusCode);
        }
    }
}
