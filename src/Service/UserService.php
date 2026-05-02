<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private readonly UserRepository $repository,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly EntityManagerInterface $entityManager,
        private readonly JWTTokenManagerInterface $jwtManager
    ) {}

    public function getUserById(int $id): User
    {
        $user = $this->repository->find($id);
        if (!$user) {
            throw new NotFoundHttpException("User not found");
        }
        return $user;
    }

    public function createUser(array $data): User
    {
        $user = new User();
        $user->setEmail($data['email']);
        $user->setRoles($data['roles'] ?? ['ROLE_USER']);

        $hashedPassword = $this->passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function updateUser(int $id, array $data): User
    {
        $user = $this->getUserById($id);

        if (isset($data['email'])) {
            $user->setEmail($data['email']);
        }

        if (isset($data['password'])) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);
        }

        if (isset($data['roles'])) {
            $user->setRoles($data['roles']);
        }

        $this->entityManager->flush();
        return $user;
    }

    public function deleteUser(int $id): void
    {
        $user = $this->getUserById($id);
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    public function login(?string $email, ?string $password): JsonResponse
    {
        if (!$email || !$password) {
            return new JsonResponse([
                'message' => 'Email and password required'
            ], 400);
        }

        $user = $this->repository->findOneBy(['email' => $email]);

        if (!$user) {
            return new JsonResponse([
                'message' => 'User not found'
            ], 404);
        }

        if (!$this->passwordHasher->isPasswordValid($user, $password)) {
            return new JsonResponse([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $this->jwtManager->create($user);

        return new JsonResponse([
            'token' => $token,
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles()
            ]
        ]);
    }
}
