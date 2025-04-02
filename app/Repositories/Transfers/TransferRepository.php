<?php

namespace App\Repositories\Transfers;

use App\Http\Resources\TransferResource;
use App\Models\MySql\Transfer;
use Illuminate\Database\Eloquent\Collection;

class TransferRepository implements TransferRepositoryInterface
{
    /**
     * Retrieve all transfers.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function all(array $filters = [], string $sortField = 'id', string $sortDirection = 'asc'): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $allowedSortFields = ['id', 'status', 'pickup', 'unload'];
        $sortField = in_array($sortField, $allowedSortFields) ? $sortField : 'id';

        $sortDirection = in_array(strtolower($sortDirection), ['asc', 'desc']) ? $sortDirection : 'asc';

        $query = Transfer::with(['driver', 'vehicle'])
            ->when(isset($filters['driver_id']), fn($q) => $q->where('driver_id', $filters['driver_id']))
            ->when(isset($filters['status']), fn($q) => $q->where('status', $filters['status']))
            ->when(isset($filters['flight_num']), fn($q) => $q->where('flight_num', 'like', "%{$filters['flight_num']}%"))
            ->orderBy($sortField, $sortDirection);

        return TransferResource::collection($query->paginate(5));
    }


    /**
     * Create a new transfer.
     *
     * @param array $data
     * @return Transfer|null
     */
    public function create(array $data): ?Transfer
    {
        return Transfer::create($data);
    }

    /**
     * Update a transfer by ID.
     *
     * @param array $data
     * @param int $id
     * @return int
     */
    public function update(array $data, int $id): int
    {
        return Transfer::where('id', $id)->update($data);
    }

    /**
     * Delete a transfer by ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return Transfer::where('id', $id)->delete();
    }

    /**
     * Find a transfer by ID.
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function find(int $id): \Illuminate\Database\Eloquent\Builder
    {
        return Transfer::with(['driver', 'vehicle'])
            ->find($id);
    }
}
