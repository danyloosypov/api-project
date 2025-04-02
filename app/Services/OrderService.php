<?php

namespace App\Services;

use App\Repositories\Orders\OrderRepositoryInterface;

class OrderService
{
    protected $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function getAllOrders(array $filters, mixed $sortBy, mixed $direction)
    {
        return $this->orderRepository->all($filters, $sortBy, $direction);
    }

    public function createOrder(array $validatedData)
    {
        return $this->orderRepository->create($validatedData);
    }

    public function findOrder($id)
    {
        return $this->orderRepository->find($id);
    }

    public function updateOrder(array $validatedData, $id)
    {
        return $this->orderRepository->update($validatedData, $id);
    }

    public function deleteOrder($id)
    {
        return $this->orderRepository->delete($id);
    }
}
