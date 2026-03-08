<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\User;
use App\Enum\OrderStatus;
use App\Service\CourseService;
use App\Service\GameService;
use App\Repository\OrderRepository;
use App\Service\UserService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class OrderService
{
    public function __construct(
        private readonly OrderRepository $repository,
        private readonly UserService $userService,
        private readonly GameService $gameService,
        private readonly CourseService $courseService
    ) {}

    public function getOrders(): array
    {
        return $this->repository->findAllSafe(10);
    }

    public function createOrder(array $data, ?User $currentUser): Order
    {
        $order = new Order();
        $reference = $data['reference'] ?? 'ORD-' . strtoupper(bin2hex(random_bytes(4)));
        $order->setReference($reference);
        $order->setStatus(OrderStatus::PENDING);

        $user = $currentUser ?? $this->userService->getUserById($data['user_id'] ?? 0);
        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }
        $order->setUser($user);

        $total = 0.0;

        if (!empty($data['game_ids'])) {
            foreach ($data['game_ids'] as $gameId) {
                $game = $this->gameService->getGameById($gameId); //Check if the game exists
                $order->addGame($game);
                $total += $game->getPrice();
            }
        }

        if (!empty($data['course_ids'])) {
            foreach ($data['course_ids'] as $courseId) {
                $course = $this->courseService->getCourseById($courseId); //check if the course exists
                $order->addCourse($course);
                $total += $course->getPrice();
            }
        }

        if ($total <= 0) {
            throw new BadRequestHttpException("An order must contain at least one game or course.");
        }
        $order->setTotal($total);

        return $this->repository->create($order);
    }

    public function getOrderById(int $id): Order
    {
        $order = $this->repository->findById($id);
        if (!$order) {
            throw new NotFoundHttpException("Order #$id not found");
        }
        return $order;
    }

    public function getOrdersByUser(int $userId): array
    {
        return $this->repository->findByCriteria(['user' => $userId]);
    }
}
