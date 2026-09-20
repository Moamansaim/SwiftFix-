<?php

namespace App\Features\CustomerRepairRequests\Repositories;

use App\Features\CustomerRepairRequests\DTOs\CustomerRepairRequestDTO;
use App\Features\CustomerRepairRequests\Interfaces\CustomerRepairRequestInterface;
use App\Features\CustomerRepairRequests\Models\CustomerRepairRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

class CustomerRepairRequestRepository implements CustomerRepairRequestInterface
{
    public function create(
        CustomerRepairRequestDTO $dto
    ): CustomerRepairRequest {

        $user = Auth::guard('sanctum')->user();

        $imagePath = null;

        if ($dto->image instanceof UploadedFile) {
            $imagePath = $dto->image->store(
                'repair-requests',
                'public'
            );
        }

        return CustomerRepairRequest::create([
            'user_id' => $dto->user_id,
            'shop_id' => $dto->shop_id,
            'device_model_id' => $dto->device_model_id,
            'service_id' => $dto->service_id,
            'description' => $dto->description,
            'image' => $imagePath,
            'phone_number' => $user->phone_number,
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