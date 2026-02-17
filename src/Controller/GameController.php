<?php

namespace App\Controller;

use App\Entity\Game;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\RecommendationService;

#[Route('/games')]
final class GameController extends AbstractController
{
    #[Route(name: 'app_game_index', methods: ['GET'])]
    public function index(GameRepository $gameRepository): Response
    {
        $games = $gameRepository->findAll();
        return $this->json($games, 200, [], ['groups' => 'game:read']);
    }

    #[Route('', name: 'app_game_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = $request->toArray();

        $game = new Game();
        $game->setTitle($data['title'] ?? 'Game without title');
        $game->setPrice($data['price'] ?? 0);
        $game->setDescription($data['description'] ?? null);
        $game->setStock($data['stock'] ?? 0);

        $user = $this->getUser();
        if ($user) {
            $game->setCreatedBy($user);
        }

        $entityManager->persist($game);
        $entityManager->flush();

        return $this->json($game, 201, [], ['groups' => 'game:read']);
    }

    #[Route('/{id}', name: 'app_game_show', methods: ['GET'])]
    public function show(Game $game): Response
    {
        return $this->json($game, 200, [], ['groups' => 'game:read']);
    }

    #[Route('/{id}/edit', name: 'app_game_edit', methods: ['PUT'])]
    public function edit(Request $request, Game $game, EntityManagerInterface $entityManager): Response
    {
        $data = $request->toArray();
        if (isset($data['title'])) {
            $game->setTitle($data['title']);
        }
        if (isset($data['description'])) {
            $game->setDescription($data['description']);
        }
        if (isset($data['price'])) {
            $game->setPrice($data['price']);
        }
        if (isset($data['stock'])) {
            $game->setStock($data['stock']);
        }

        $entityManager->flush();

        return $this->json(['message' => 'Game updated'], Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'app_game_delete', methods: ['DELETE'])]
    public function delete(Game $game, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($game);
        $entityManager->flush();

        return $this->json(['message' => 'Gamae deleted'], 200);
    }

    #[Route('/recommend/{title}', name: 'app_game_show', methods: ['GET'])]
    public function recommend(
        string $title,
        RecommendationService $recommendationService
    ): Response {

        $recommendations = $recommendationService->getRecommendationsForGame($title);
        return $this->json([
            'game' => $title,
            'recommendations' => $recommendations
        ], 200, [], ['groups' => 'game:read']);
    }
}
