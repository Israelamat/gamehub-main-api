<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\User;
use App\Enum\OrderStatus;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/order')]
class OrderController extends AbstractController
{
    #[Route('', name: 'app_order', methods: ['GET'])]
    public function index(OrderRepository $orderRepository): Response
    {
        $orders = $orderRepository->findBy([], ['id' => 'ASC'], 10);
        return $this->json($orders, 200, [], ['groups' => 'order:read']);
    }

    #[Route('', name: 'app_order_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = $request->toArray();

        $order = new Order();

        $order->setReference($data['reference'] ?? 'ORD-' . strtoupper(bin2hex(random_bytes(4))));
        $order->setTotal((float)$data['total']);
        $order->setStatus(OrderStatus::PENDING);

        $user = $this->getUser() ?? $entityManager->getRepository(User::class)->find($data['user_id']);

        if (!$user) {
            return $this->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        $order->setUser($user);

        $entityManager->persist($order);
        $entityManager->flush();

        return $this->json(['message' => 'Order created', 'reference' => $order->getReference()], Response::HTTP_CREATED);
    }
}
