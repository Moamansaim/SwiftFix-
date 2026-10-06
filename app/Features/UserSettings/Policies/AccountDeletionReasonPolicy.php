<?php

namespace App\Features\UserSettings\Policies;

use App\Features\UserSettings\Models\AccountDeletionReason;
use App\Features\Auth\Models\User;

class AccountDeletionReasonPolicy
{
    /**
     * Toggle the activation status of an account deletion reason.
     */
    public function toggle(
        User $user,
        AccountDeletionReason $accountDeletionReason
    ): bool {
        return $user->can('تفعيل وإلغاء تفعيل أسباب حذف الحساب');
    }
}