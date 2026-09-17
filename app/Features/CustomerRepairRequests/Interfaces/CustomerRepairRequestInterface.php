<?php

namespace App\Features\CustomerRepairRequest\Repositories;

use App\Features\CustomerRepairRequest\DTOs\CustomerRepairRequestDTO;
use App\Features\CustomerRepairRequest\Models\CustomerRepairRequest;

interface CustomerRepairRequestInterface
{
    public function create(
        CustomerRepairRequestDTO $dto
    ): CustomerRepairRequest;

    public function findById(
        int $id
    ): CustomerRepairRequest;

    public function delete(
        CustomerRepairRequest $repairRequest
    ): void;

    public function updateStatus(
        CustomerRepairRequest $repairRequest,
        string $status
    ): CustomerRepairRequest;
}