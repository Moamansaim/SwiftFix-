<?php

namespace App\Features\CustomerRepairRequests\Interfaces;

use App\Features\CustomerRepairRequests\DTOs\CustomerRepairRequestDTO;
use App\Features\CustomerRepairRequests\Models\CustomerRepairRequest;

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