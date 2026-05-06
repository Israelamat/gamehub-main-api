<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

#[Route('/api/user')]
final class UserController extends AbstractController
{
    #[Route('/login', name: 'api_login', methods: ['POST'])]
    public function login(Request $request, UserService $userService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        return $userService->login(
            $data['email'] ?? null,
            $data['password'] ?? null
        );
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->json($user, 200, [], ['groups' => 'user:read']);
    }

    #[Route('', name: 'app_user_create', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        JWTTokenManagerInterface $jwtManager
    ): Response {

        $data = $request->toArray();

        $user = new User();
        $user->setEmail($data['email']);
        $user->setRoles(['ROLE_USER']);

        $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);

        $entityManager->persist($user);
        $entityManager->flush();

        $token = $jwtManager->create($user);

        return $this->json([
            'token' => $token,
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getUserIdentifier(),
                'roles' => $user->getRoles()
            ]
        ], Response::HTTP_CREATED);
    }


    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['PUT'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(
        Request $request,
        User $user,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {

        $data = $request->toArray();

        if (isset($data['email'])) {
            $user->setEmail($data['email']);
        }
        if (isset($data['password'])) {
            $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);
        }

        $entityManager->flush();
        return $this->json(['message' => 'User updated'], Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(User $user, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($user);
        $entityManager->flush();
        return $this->json(['message' => 'User deleted'], Response::HTTP_OK);
    }
}
