<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Order;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/review')]
class ReviewController extends AbstractController
{
    #[Route('', name: 'app_review')]
    public function index(GameRepository $gameRepository): Response
    {
        $game = $gameRepository->findAll();
        return $this->json($game, 200, [], ['groups' => 'review:read']);
    }

    #[Route('', name: 'app_review_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = $request->toArray();

        $order = new Order();
        $order->setReference($data['reference']);
        $order->setTotal($data['total']);
        $order->setStatus($data['status']);

        $user = $this->getUser();
        if ($user) {
            $order->setUser($user);
        }

        $entityManager->persist($order);
        $entityManager->flush();

        return $this->json($order, 201, [], ['groups' => 'order:read']);
    }
}
