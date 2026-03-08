<?php

namespace App\Controller;

use App\Service\OrderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/order')]
class OrderController extends AbstractController
{
    public function __construct(
        private readonly OrderService $orderService
    ) {}

    #[Route('/{id}', name: 'app_order_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function index(int $id): Response
    {
        $order = $this->orderService->getOrderById($id);
        return $this->json($order, 200, [], ['groups' => 'order:read']);
    }

    #[Route('', name: 'app_order_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $order = $this->orderService->createOrder(
            $request->toArray(),
            $this->getUser()
        );

        return $this->json([
            'message' => 'Order created',
            'reference' => $order->getReference(),
            'total' => $order->getTotal()
        ], Response::HTTP_CREATED);
    }

    #[Route('/user/{id}', name: 'app_order_user', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function getOrdersByUser(int $id): Response
    {
        $orders = $this->orderService->getOrdersByUser($id);
        return $this->json($orders, 200, [], ['groups' => 'order:read']);
    }
}
