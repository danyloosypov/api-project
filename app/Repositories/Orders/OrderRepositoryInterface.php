<?php

namespace App\Repositories\Orders;

use App\Http\Resources\OrderResource;
use App\Models\MySql\Order;

interface OrderRepositoryInterface
{
    public function all(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection;

    public function create(array $data): ?Order;

    public function update(array $data, int $id): int;

    public function delete(int $id): bool;

    public function find(int $id): ?Order;
}
