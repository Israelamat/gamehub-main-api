<?php

namespace App\Service;

use App\Entity\Review;
use App\Entity\Game;
use App\Entity\User;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReviewService
{
    public function __construct(
        private readonly ReviewRepository $repository,
        private readonly EntityManagerInterface $em
    ) {}

    public function getReviews(): array
    {
        return $this->repository->findBy([], ['id' => 'DESC'], 10);
    }

    public function getReviewsByAppId(int $gameId): array
    {
        $reviews = $this->repository->findByGameId($gameId);

        if (empty($reviews)) {
            throw new NotFoundHttpException("No reviews found for appId $gameId");
        }

        return $reviews;
    }

    public function createReview(array $data, ?User $user = null): Review
    {
        if (!isset($data['rating']) || $data['rating'] < 1 || $data['rating'] > 5) {
            throw new BadRequestHttpException("Rating must be between 1 and 5.");
        }

        if (!isset($data['game_id'])) {
            throw new BadRequestHttpException("game_id is required.");
        }

        $game = $this->em->getRepository(Game::class)->find($data['game_id']);
        if (!$game) {
            throw new NotFoundHttpException("Game not found.");
        }

        if (!$user && isset($data['user_id'])) {
            $user = $this->em->getRepository(User::class)->find($data['user_id']);
        }

        if (!$user) {
            throw new NotFoundHttpException("User not found.");
        }

        $review = new Review();
        $review->setRating($data['rating']);
        $review->setComment($data['comment'] ?? null);
        $review->setGame($game);
        $review->setUser($user);

        $this->em->persist($review);
        $this->em->flush();

        return $review;
    }
}
