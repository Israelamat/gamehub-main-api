<?php

namespace App\Service;

use App\Entity\Game;
use App\Repository\GameRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GameService
{
    public function __construct(
        private readonly GameRepository $repository
    ) {}

    public function getGames(): array
    {
        return $this->repository->findAllSafe();
    }

    public function getGamesPaginated(
        int $page,
        int $limit
    ): array {
        $offset = ($page - 1) * $limit;

        return $this->repository->findPaginated(
            $limit,
            $offset
        );
    }

    public function getGamesFilteredPaginated(
        array $filters,
        int $page,
        int $limit
    ): array {
        $offset = ($page - 1) * $limit;

        return $this->repository->findFilteredGames(
            $filters['search'] ?? null,
            $filters['tag'] ?? null,
            $filters['maxPrice'] ?? null,
            $filters['sort'] ?? null,
            $limit,
            $offset
        );
    }

    public function getGameById(int $id): Game
    {
        $results = $this->repository->findByCriteria(['id' => $id]);
        if (empty($results)) {
            throw new NotFoundHttpException("Game with internal ID $id not found.");
        }

        return $results[0];
    }

    public function getGameByAppId(int $appId): Game
    {
        $game = $this->repository->findOneBy(['appId' => $appId]);

        if (!$game) {
            throw new NotFoundHttpException("Game with appId $appId not found");
        }

        return $game;
    }

    public function getGamesByIds(array $ids): array
    {
        return $this->repository->findBy(['appId' => $ids]);
    }

    public function getGameBySteamId(int $appId): Game
    {
        $results = $this->repository->findByCriteria(['appId' => $appId]);
        if (empty($results)) {
            throw new NotFoundHttpException("Steam Game with AppID $appId not found in our catalog.");
        }

        return $results[0];
    }
}
