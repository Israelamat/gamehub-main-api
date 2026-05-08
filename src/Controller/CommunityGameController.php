<?php

namespace App\Controller;

use App\Service\CommunityGameService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/community-game')]
final class CommunityGameController extends AbstractController
{
    public function __construct(
        private readonly CommunityGameService $communityGameService
    ) {}

    #[Route('', name: 'app_community_game_index', methods: ['GET'])]
    public function index(): Response
    {
        $communityGames = $this->communityGameService->getCommunityGames();

        return $this->json(
            $communityGames,
            200,
            [],
            ['groups' => 'community_game:read']
        );
    }

    #[Route('', name: 'app_community_game_new', methods: ['POST'])]
    public function new(Request $request): Response
    {
        $communityGame = $this->communityGameService->createCommunityGame(
            $request->toArray(),
            $this->getUser()
        );

        return $this->json([
            'message' => 'Community game created',
            'id' => $communityGame->getId()
        ], Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'app_community_game_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function showById(int $id): Response
    {
        $communityGame = $this->communityGameService->getCommunityGameById($id);

        return $this->json(
            $communityGame,
            200,
            [],
            ['groups' => 'community_game:read']
        );
    }

    #[Route('/by-ids', name: 'app_community_game_by_ids', methods: ['POST'])]
    public function getByIds(Request $request): Response
    {
        $data = $request->toArray();

        $ids = $data['ids'] ?? [];

        if (!is_array($ids) || empty($ids)) {
            return $this->json([
                'message' => 'Ids array is required'
            ], Response::HTTP_BAD_REQUEST);
        }

        $communityGames = $this->communityGameService->getCommunityGamesByIds($ids);

        return $this->json(
            $communityGames,
            200,
            [],
            ['groups' => 'community_game:read']
        );
    }

    #[Route('/{id}', name: 'app_community_game_edit', methods: ['PUT'], requirements: ['id' => '\d+'])]
    public function edit(int $id, Request $request): Response
    {
        $this->communityGameService->updateCommunityGame(
            $id,
            $request->toArray()
        );

        return $this->json([
            'message' => 'Community game updated'
        ]);
    }

    #[Route('/{id}', name: 'app_community_game_delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(int $id): Response
    {
        $this->communityGameService->deleteCommunityGame($id);

        return $this->json(
            null,
            Response::HTTP_NO_CONTENT
        );
    }
}
