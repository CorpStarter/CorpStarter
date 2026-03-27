<?php

namespace App\Controller;

use App\Entity\Users;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/api/auth', name: 'api_auth_')]
class AuthController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UsersRepository $usersRepository,
        private UserPasswordHasherInterface $passwordHasher,
        private ValidatorInterface $validator
    ) {}

    #[Route('/signup', name: 'signup', methods: ['POST'])]
    public function signup(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            // Validation des données
            if (!isset($data['username'], $data['email'], $data['password'], $data['first_name'], $data['last_name'])) {
                return new JsonResponse(['error' => 'Missing required fields'], 400);
            }

            // Vérifier si l'utilisateur existe déjà
            $existingUser = $this->usersRepository->findOneBy(['email' => $data['email']]);
            if ($existingUser) {
                return new JsonResponse(['error' => 'User already exists'], 409);
            }

            // Créer un nouvel utilisateur
            $user = new Users();
            $user->setUsername($data['username']);
            $user->setEmail($data['email']);
            $user->setFirstName($data['first_name']);
            $user->setLastName($data['last_name']);
            $user->setPasswordHash($this->passwordHasher->hashPassword($user, $data['password']));
            $user->setEmailConfirmed(false);
            $user->setTermsAccepted($data['terms_accepted'] ?? false);
            $user->setCreationDate(new \DateTime());

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return new JsonResponse([
                'message' => 'User created successfully',
                'user' => [
                    'id' => $user->getId(),
                    'username' => $user->getUsername(),
                    'email' => $user->getEmail()
                ]
            ], 201);
            // plus tard envoyer un mail de confirmation en se servant du token de connexion pour vérifier l'email
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/signin', name: 'signin', methods: ['POST'])]
    public function signin(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (!isset($data['email'], $data['password'])) {
                return new JsonResponse(['error' => 'Email and password required'], 400);
            }

            $user = $this->usersRepository->findOneBy(['email' => $data['email']]);

            if (!$user) {
                return new JsonResponse(['error' => 'Invalid credentials'], 401);
            }

            if (!$this->passwordHasher->isPasswordValid($user, $data['password'])) {
                return new JsonResponse(['error' => 'Invalid credentials'], 401);
            }

            if (!$user->isEmailConfirmed()) {
                return new JsonResponse(['error' => 'Email not verified'], 403);
            }

            // Generate token and save it
            $token = bin2hex(random_bytes(16));
            $user->setConectionToken($token);
            $user->setTokenDate(new \DateTime());
            $this->entityManager->flush();

            return new JsonResponse([
                'message' => 'Sign in successful',
                'user' => [
                    'id' => $user->getId(),
                    'username' => $user->getUsername(),
                    'email' => $user->getEmail(),
                    'first_name' => $user->getFirstName(),
                    'last_name' => $user->getLastName(),
                    'token' => $token
                ]
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/verify-email', name: 'verify_email', methods: ['POST'])]
    public function verifyEmail(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (!isset($data['user_id'])) {
                return new JsonResponse(['error' => 'User ID required'], 400);
            }

            $user = $this->usersRepository->find($data['user_id']);

            if (!$user) {
                return new JsonResponse(['error' => 'User not found'], 404);
            }

            $user->setEmailConfirmed(true);
            $this->entityManager->flush();

            return new JsonResponse(['message' => 'Email verified successfully']);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
}
