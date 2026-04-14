<?php

namespace App\Service;

use App\Entity\Users;
use App\Entity\UserTypes;
use App\Repository\UsersRepository;
use App\Repository\UserTypesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthService
{
    public function __construct(
        private UsersRepository $usersRepository,
        private UserTypesRepository $userTypesRepository,
        private EntityManagerInterface $entityManager,
    ) {}

    public function register(
        string $username,
        string $lastName,
        string $firstName,
        string $password,
        string $email,
        string $userTypeName
    ): array {
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new BadRequestHttpException('Invalid email format');
        }

        // Check if email already exists
        $existingUser = $this->usersRepository->findOneBy(['email' => $email]);
        if ($existingUser) {
            throw new ConflictHttpException('Email already exists');
        }

        // Get user type
        $userType = $this->userTypesRepository->findOneBy(['name' => $userTypeName]);
        if (!$userType) {
            throw new BadRequestHttpException('Invalid user type');
        }

        // Create new user
        $user = new Users();
        $user->setUsername($username);
        $user->setLastName($lastName);
        $user->setFirstName($firstName);
        $user->setEmail($email);
        $user->setPasswordHash(password_hash($password, PASSWORD_BCRYPT));
        $user->setEmailConfirmed(false);
        $user->setTermsAccepted(true);
        $user->setCreationDate(new \DateTime());
        $user->setUserType($userType);

        // Generate token
        $token = bin2hex(random_bytes(32));
        $user->setToken($token);
        $user->setTokenDate(new \DateTime());

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return [
            'id' => $user->getId(),
            'token' => $token,
            'message' => 'User registered successfully. Please verify your email.'
        ];
    }

    public function verifyEmail(string $token): array
    {
        $user = $this->usersRepository->findOneBy(['token' => $token]);

        if (!$user) {
            throw new NotFoundHttpException('User not found or invalid token');
        }

        $user->setEmailConfirmed(true);
        $this->entityManager->flush();

        return ['message' => 'Email verified successfully'];
    }

    public function login(string $email, string $password): array
    {
        $user = $this->usersRepository->findOneBy(['email' => $email]);

        if (!$user) {
            throw new UnauthorizedHttpException('', 'Invalid credentials');
        }

        if (!password_verify($password, $user->getPasswordHash())) {
            throw new UnauthorizedHttpException('', 'Invalid credentials');
        }

        if (!$user->isEmailConfirmed()) {
            throw new UnauthorizedHttpException('', 'Email not confirmed');
        }

        if (!$user->isTermsAccepted()) {
            throw new UnauthorizedHttpException('', 'Terms not accepted');
        }

        // Generate new token
        $token = bin2hex(random_bytes(32));
        $user->setToken($token);
        $user->setTokenDate(new \DateTime());

        $this->entityManager->flush();

        return [
            'token' => $token,
            'user_id' => $user->getId(),
            'role' => $user->getUserType()->getName(),
            'message' => 'Logged in successfully'
        ];
    }

    public function validateToken(string $token): ?Users
    {
        return $this->usersRepository->findOneBy(['token' => $token]);
    }
}
