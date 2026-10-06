<?php

namespace App\Features\CustomerRepairRequests\Policies;

use App\Features\Auth\Models\User;
use App\Features\CustomerRepairRequests\Models\CustomerRepairRequest;

class CustomerRepairRequestPolicy
{
    /**
     * Determine whether the user can view customer repair requests.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('عرض طلبات الصيانة');
    }

    /**
     * Determine whether the user can delete a customer repair request.
     */
    public function delete(
        User $user,
        CustomerRepairRequest $customerRepairRequest
    ): bool {
        return $user->can('حذف طلبات الصيانة');
    }

    /**
     * Determine whether the user can approve a customer repair request.
     */
    public function approve(
        User $user,
        CustomerRepairRequest $customerRepairRequest
    ): bool {
        return $user->can('الموافقة على طلبات الصيانة');
    }

    /**
     * Determine whether the user can reject a customer repair request.
     */
    public function reject(
        User $user,
        CustomerRepairRequest $customerRepairRequest
    ): bool {
        return $user->can('رفض طلبات الصيانة');
    }
}