<?php

namespace App\Features\CustomerRepairRequest\UseCases;

use App\Features\CustomerRepairRequest\DTOs\CustomerRepairRequestDTO;
use App\Features\CustomerRepairRequest\Models\CustomerRepairRequest;
use App\Features\CustomerRepairRequest\Notifications\RepairRequestApprovedNotification;
use App\Features\CustomerRepairRequest\Notifications\RepairRequestRejectedNotification;
use App\Features\CustomerRepairRequest\Repositories\CustomerRepairRequestInterface;
use App\Features\CustomerRepairRequest\Services\CustomerRepairRequestMail;
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
}