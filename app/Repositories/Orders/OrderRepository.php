<?php

namespace App\Repositories\Orders;

use App\Http\Resources\OrderResource;
use App\Models\MySql\Order;

class OrderRepository implements OrderRepositoryInterface
{
    /**
     * Get all orders with filtering and sorting applied.
     *
     * @param array $filters
     * @param string $sortBy
     * @param string $direction
     * @return Collection
     */
    public function all(array $filters = [], string $sortBy = 'order_date', string $direction = 'desc'): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $query = Order::query();

        // Apply filters
        foreach ($filters as $key => $value) {
            if (!empty($value)) {
                if ($key == 'phone' || $key == 'email' || $key == 'comment' || $key == 'flight_num') {
                    $query->where($key, 'like', '%' . $value . '%');
                }
                if ($key == 'order_date' || $key == 'arrival_date') {
                    // For date range, assuming date format is YYYY-MM-DD
                    $query->whereDate($key, $value);
                }
            }
        }

        // Sorting
        $query->orderBy($sortBy, $direction);

        $results = $query->paginate(5);

        return OrderResource::collection($results);
    }

    /**
     * Create a new Order.
     *
     * @param array $data
     * @return Order|null
     */
    public function create(array $data): ?Order
    {
        return Order::create($data);
    }

    /**
     * Update a order by ID.
     *
     * @param array $data
     * @param int $id
     * @return int
     */
    public function update(array $data, int $id): int
    {
        return Order::where('id', $id)->update($data);
    }

    /**
     * Delete a order by ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return Order::where('id', $id)->delete();
    }

    /**
     * Find a order by ID.
     *
     * @param int $id
     * @return Order|null
     */
    public function find(int $id): ?Order
    {
        return Order::find($id);
    }
}
