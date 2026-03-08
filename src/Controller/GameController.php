<?php

namespace App\Controller;

use App\Service\GameService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\RecommendationService;

#[Route('/games')]
final class GameController extends AbstractController
{
    public function __construct(
        private readonly GameService $gameService
    ) {}

    #[Route('', name: 'app_game_index', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $filters = $request->query->all();

        $games = empty($filters)
            ? $this->gameService->getGames()
            : $this->gameService->getGamesByFilters($filters);

        return $this->json($games, 200, [], ['groups' => 'game:read']);
    }

    #[Route('/{id}', name: 'app_game_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function showById(int $id): JsonResponse
    {
        $game = $this->gameService->getGameById($id);
        return $this->json($game, 200, [], ['groups' => 'game:read']);
    }

    #[Route('/steam/{appId}', name: 'app_steam_game_show', methods: ['GET'], requirements: ['appId' => '\d+'])]
    public function showBySteamID(int $appId): JsonResponse
    {
        $game = $this->gameService->getGameBySteamId($appId);

        return $this->json($game, 200, [], ['groups' => 'game:read']);
    }

    #[Route('/recommend/{title}', name: 'app_game_show_recommend', methods: ['GET'])]
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

    // #[Route('/recommend/{id}', name: 'app_game_id_shoe', methods: ['GET'])]
    // public function recommendById(
    //     int $id, RecommendationService $recommendationService): Response
    // {
    //     $recommendations = $recommendationService->getRecommendationsForGame_id($game->getId());
    //     return $this->json([
    //         'game' => $game,
    //         'recommendations' => $recommendations
    //     ], 200, [], ['groups' => 'game:read']);
    // }
}
