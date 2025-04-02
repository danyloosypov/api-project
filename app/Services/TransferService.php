<?php

namespace App\Services;

use App\Repositories\Transfers\TransferRepositoryInterface;

class TransferService
{
    protected $transferRepository;

    public function __construct(TransferRepositoryInterface $transferRepository)
    {
        $this->transferRepository = $transferRepository;
    }

    /**
     * Get all transfers.
     */
    public function getAllTransfers(array $filters = [], $sortField = 'id', $sortDirection = 'asc')
    {
        return $this->transferRepository->all($filters, $sortField, $sortDirection);
    }

    /**
     * Create a new transfer.
     *
     * @param array $data
     */
    public function createTransfer(array $data)
    {
        return $this->transferRepository->create($data);
    }

    /**
     * Update a transfer by ID.
     *
     * @param array $data
     * @param int $id
     */
    public function updateTransfer(array $data, int $id)
    {
        return $this->transferRepository->update($data, $id);
    }

    /**
     * Delete a transfer by ID.
     *
     * @param int $id
     */
    public function deleteTransfer(int $id)
    {
        return $this->transferRepository->delete($id);
    }

    /**
     * Find a transfer by ID.
     *
     * @param int $id
     */
    public function findTransfer(int $id)
    {
        return $this->transferRepository->find($id);
    }
}
