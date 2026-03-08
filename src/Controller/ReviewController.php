<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Review;
use App\Entity\User;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/review')]
class ReviewController extends AbstractController
{
    #[Route('', name: 'app_review', methods: ['GET'])]
    public function index(ReviewRepository $reviewRepository): Response
    {
        $review = $reviewRepository->findBy([], ['id' => 'ASC'], 10);
        return $this->json($review, 200, [], ['groups' => 'review:read']);
    }

    #[Route('', name: 'app_review_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = $request->toArray();

        $review = new Review();
        $review->setRating($data['rating']);
        $review->setComment($data['comment'] ?? null);

        $game = $entityManager->getRepository(Game::class)->find($data['game_id']);
        if (!$game) {
            return $this->json(['message' => 'Game not found'], Response::HTTP_NOT_FOUND);
        }
        $review->setGame($game);

        $user = $this->getUser() ?? $entityManager->getRepository(User::class)->find($data['user_id']);
        if (!$user) { {
                return $this->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
            }
        }
        $review->setUser($user);

        $entityManager->persist($review);
        $entityManager->flush();

        return $this->json(['message' => 'Review created'], Response::HTTP_CREATED);
    }
}
