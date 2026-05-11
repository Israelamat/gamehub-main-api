<?php

namespace App\Service;

use App\Entity\CommunityGame;
use App\Entity\User;
use App\Repository\CommunityGameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CommunityGameService
{
    public function __construct(
        private readonly CommunityGameRepository $repository,
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function getCommunityGames(): array
    {
        return $this->repository->findAllSafe();
    }

    public function getCommunityGameById(int $id): CommunityGame
    {
        $communityGame = $this->repository->findById($id);

        if (!$communityGame) {
            throw new NotFoundHttpException("Community game not found");
        }

        return $communityGame;
    }

    public function getCommunityGamesByIds(array $ids): array
    {
        return $this->repository->findBy(['id' => $ids]);
    }

    public function createCommunityGame(array $data, ?User $currentUser): CommunityGame
    {
        $communityGame = new CommunityGame();

        $communityGame->setTitle($data['title'] ?? 'Untitled Game');
        $communityGame->setAuthor($data['author'] ?? 'Unknown Author');
        $communityGame->setImageBase64($data['imageBase64'] ?? '');
        $communityGame->setRating($data['rating'] ?? 0);
        $communityGame->setPrice($data['price'] ?? 0);

        if (!$currentUser) {
            throw new NotFoundHttpException('User not authenticated');
        }

        $communityGame->setCreatedBy($currentUser);

        return $this->repository->create($communityGame);
    }

    public function updateCommunityGame(int $id, array $data): CommunityGame
    {
        $communityGame = $this->getCommunityGameById($id);

        if (isset($data['title'])) {
            $communityGame->setTitle($data['title']);
        }

        if (isset($data['author'])) {
            $communityGame->setAuthor($data['author']);
        }

        if (isset($data['imageBase64'])) {
            $communityGame->setImageBase64($data['imageBase64']);
        }

        if (isset($data['rating'])) {
            $communityGame->setRating($data['rating']);
        }

        if (isset($data['price'])) {
            $communityGame->setPrice($data['price']);
        }

        return $this->repository->update($communityGame, $data);
    }

    public function deleteCommunityGame(int $id): void
    {
        $communityGame = $this->getCommunityGameById($id);

        $this->repository->delete($communityGame);
    }
}
