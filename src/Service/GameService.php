<?php

namespace App\Service;

use App\Entity\Game;
use App\Repository\GameRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
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

    public function getGamesByFilters(array $filters): array
    {
        $allowedFilters = [
            'id',
            'appId',
            'developer',
            'price',
            'title',
            'genres',
            'tags'
        ];

        foreach ($filters as $key => $value) {
            if (!in_array($key, $allowedFilters)) {
                throw new BadRequestHttpException("Filter '$key' is not allowed.");
            }

            if ($key === 'price' && $value < 0) {
                throw new BadRequestHttpException("Price search value cannot be negative.");
            }
        }

        return $this->repository->findByCriteria($filters);
    }

    public function getGameById(int $id): Game
    {
        $results = $this->repository->findByCriteria(['id' => $id]);
        if (empty($results)) {
            throw new NotFoundHttpException("Game with internal ID $id not found.");
        }

        return $results[0];
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
