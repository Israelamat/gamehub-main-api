<?php

namespace App\Controller;

use App\Service\ReviewService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/review')]
class ReviewController extends AbstractController
{
    public function __construct(
        private readonly ReviewService $service
    ) {}

    #[Route('', methods: ['GET'])]
    public function index(): Response
    {
        return $this->json($this->service->getReviews(), 200, [], ['groups' => 'review:read']);
    }

    #[Route('/game/{appId}', methods: ['GET'])]
    public function byAppId(int $appId): Response
    {
        return $this->json($this->service->getReviewsByAppId($appId), 200, [], ['groups' => 'review:read']);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $review = $this->service->createReview(
            $request->toArray(),
            $this->getUser()
        );

        return $this->json($review, Response::HTTP_CREATED, [], ['groups' => 'review:read']);
    }
}
