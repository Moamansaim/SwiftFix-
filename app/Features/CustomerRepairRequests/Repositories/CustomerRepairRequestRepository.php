<?php

namespace App\Features\CustomerRepairRequests\Repositories;

use App\Features\CustomerRepairRequests\DTOs\CustomerRepairRequestDTO;
use App\Features\CustomerRepairRequests\Interfaces\CustomerRepairRequestInterface;
use App\Features\CustomerRepairRequests\Models\CustomerRepairRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CustomerRepairRequestRepository implements CustomerRepairRequestInterface
{
    /**
     * Create a new customer repair request.
     *
     * @param CustomerRepairRequestDTO $dto
     *        The validated repair request data.
     *
     * @return CustomerRepairRequest
     *         The newly created repair request.
     *
     * @hint Stores the uploaded image if provided, then creates
     *        the repair request using the authenticated user's phone number.
     */
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

    /**
     * Find a repair request by its ID.
     *
     * @param int $id
     *        The ID of the repair request.
     *
     * @return CustomerRepairRequest
     *         The requested repair request.
     *
     * @hint Throws ModelNotFoundException if the request does not exist.
     */
    public function findById(
        int $id
    ): CustomerRepairRequest {
        return CustomerRepairRequest::findOrFail($id);
    }

    /**
     * Delete a customer repair request.
     *
     * @param CustomerRepairRequest $repairRequest
     *        The repair request to delete.
     *
     * @return void
     *
     * @hint Deletes the associated image from storage before
     *        deleting the database record.
     */
    public function delete(
        CustomerRepairRequest $repairRequest
    ): void {
        if ($repairRequest->image) {
            Storage::disk('public')->delete(
                $repairRequest->image
            );
        }

        $repairRequest->delete();
    }

    /**
     * Update the status of a repair request.
     *
     * @param CustomerRepairRequest $repairRequest
     *        The repair request whose status will be updated.
     *
     * @param string $status
     *        The new repair request status.
     *
     * @return CustomerRepairRequest
     *         The updated repair request.
     *
     * @hint Refreshes the model after updating the status.
     */
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