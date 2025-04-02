<?php

namespace App\Repositories\Transfers;

use App\Http\Resources\TransferResource;
use App\Models\MySql\Transfer;

interface TransferRepositoryInterface
{
    public function all(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection;

    public function create(array $data): ?Transfer;

    public function update(array $data, int $id): int;

    public function delete(int $id): bool;

    public function find(int $id): \Illuminate\Database\Eloquent\Builder;
}
