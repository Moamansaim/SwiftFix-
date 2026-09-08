<?php

namespace App\Features\CustomerRepairRequests\UseCases;

use App\Features\CustomerRepairRequests\DTOs\CustomerRepairRequestDTO;
use App\Features\CustomerRepairRequests\Interfaces\CustomerRepairRequestInterface;
use App\Features\CustomerRepairRequests\Models\CustomerRepairRequest;
use App\Features\CustomerRepairRequests\Notifications\RepairRequestApprovedNotification;
use App\Features\CustomerRepairRequests\Notifications\RepairRequestRejectedNotification;
use App\Features\CustomerRepairRequests\Services\CustomerRepairRequestMail;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CustomerRepairRequestUseCase
{
    public function __construct(
        private CustomerRepairRequestInterface $repository,
        private CustomerRepairRequestMail $mail
    ) {}

    public function create(
        CustomerRepairRequestDTO $dto
    ): CustomerRepairRequest {

        return $this->repository->create($dto);
    }

    public function delete(
        int $id
    ): void {

        $repairRequest = $this->repository->findById($id);

        $this->repository->delete($repairRequest);
    }

    public function approve(
        int $id
    ): CustomerRepairRequest {

        $repairRequest = $this->repository->findById($id);

        if ($repairRequest->status !== 'pending') {
            throw new RuntimeException(
                'تمت معالجة طلب الصيانة مسبقًا.'
            );
        }

        $repairRequest = DB::transaction(function () use (
            $repairRequest
        ) {
            return $this->repository->updateStatus(
                $repairRequest,
                'approved'
            );
        });

        $repairRequest->load('user');

        $this->mail->sendApproval($repairRequest);

        $repairRequest->user->notify(
            new RepairRequestApprovedNotification(
                $repairRequest
            )
        );

        return $repairRequest;
    }

    public function reject(
        int $id
    ): CustomerRepairRequest {

        $repairRequest = $this->repository->findById($id);

        if ($repairRequest->status !== 'pending') {
            throw new RuntimeException(
                'تمت معالجة طلب الصيانة مسبقًا.'
            );
        }

        $repairRequest = DB::transaction(function () use (
            $repairRequest
        ) {
            return $this->repository->updateStatus(
                $repairRequest,
                'rejected'
            );
        });

        $repairRequest->load('user');

        $this->mail->sendRejection($repairRequest);

        $repairRequest->user->notify(
            new RepairRequestRejectedNotification(
                $repairRequest
            )
        );

        return $repairRequest;
    }

    /**
     * Mark a customer repair request as completed.
     *
     * This method retrieves the repair request by its ID and updates
     * its status to completed.
     *
     * The request must be approved before it can be marked as completed.
     *
     * @param int $id
     *        The unique identifier of the repair request.
     *
     * @return CustomerRepairRequest
     *         Returns the updated repair request.
     *
     * @hint Called when the shop owner completes the repair request.
     */
    public function complete(int $id) 
    {
        $repairRequest = CustomerRepairRequest::findOrFail($id);

        if ($repairRequest->status !== 'approved') {
            throw new RuntimeException(
                'لا يمكن إكمال طلب الصيانة قبل الموافقة عليه.'
            );
        }

        $repairRequest->update([
            'status' => 'completed',
        ]);
    }
}