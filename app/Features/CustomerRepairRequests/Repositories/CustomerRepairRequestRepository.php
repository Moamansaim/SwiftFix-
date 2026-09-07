<?php

namespace App\Features\CustomerRepairRequest\Repositories;

use App\Features\CustomerRepairRequest\DTOs\CustomerRepairRequestDTO;
use App\Features\CustomerRepairRequest\Models\CustomerRepairRequest;
use App\Features\CustomerRepairRequest\Repositories\CustomerRepairRequestInterface;

class CustomerRepairRequestRepository
    implements CustomerRepairRequestInterface
{
    public function create(
        CustomerRepairRequestDTO $dto
    ): CustomerRepairRequest {

        return CustomerRepairRequest::create([
            'user_id' => $dto->user_id,
            'shop_id' => $dto->shop_id,
            'device_model_id' => $dto->device_model_id,
            'service_id' => $dto->service_id,
            'description' => $dto->description,
            'image' => $dto->image,
            'phone_number' => $dto->phone_number,
            'address' => $dto->address,
        ]);
    }

    public function findById(
        int $id
    ): CustomerRepairRequest {

        return CustomerRepairRequest::findOrFail($id);
    }

    public function delete(
        CustomerRepairRequest $repairRequest
    ): void {

        $repairRequest->delete();
    }

    public function updateStatus(
        CustomerRepairRequest $repairRequest,
        string $status
    ): CustomerRepairRequest {

        $repairRequest->update([
            'status' => $status,
        ]);

        return $repairRequest->refresh();
    }
}