<?php

namespace App\Features\ShopOwner\Policies;

use App\Features\Auth\Models\User;
use App\Features\ShopOwner\Models\ShopOwnerVerification;

class ShopOwnerVerificationPolicy
{
    /**
     * Determine whether the user can view all shop owners.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('عرض أصحاب الورش');
    }

    /**
     * Determine whether the user can freeze a shop owner account.
     */
    public function freeze(User $user): bool
    {
        return $user->can('تجميد حساب صاحب الورشة');
    }

    /**
     * Determine whether the user can unfreeze a shop owner account.
     */
    public function unfreeze(User $user): bool
    {
        return $user->can('فك تجميد حساب صاحب الورشة');
    }

    /**
     * Determine whether the user can permanently delete
     * a shop owner account and shop.
     */
    public function delete(User $user): bool
    {
        return $user->can('حذف حساب صاحب الورشة');
    }

    /**
     * Determine whether the user can view
     * all shop owner verification requests.
     */
    public function viewVerifications(User $user): bool
    {
        return $user->can('عرض طلبات تحقق أصحاب الورش');
    }

    /**
     * Determine whether the user can approve
     * a shop owner verification request.
     */
    public function approveVerification(
        User $user,
        ShopOwnerVerification $verification
    ): bool {
        return $user->can('الموافقة على طلب تحقق صاحب الورشة');
    }

    /**
     * Determine whether the user can reject
     * a shop owner verification request.
     */
    public function refuseVerification(
        User $user,
        ShopOwnerVerification $verification
    ): bool {
        return $user->can('رفض طلب تحقق صاحب الورشة');
    }

    /**
     * Determine whether the user can delete
     * a shop owner verification request.
     */
    public function deleteVerification(
        User $user,
        ShopOwnerVerification $verification
    ): bool {
        return $user->can('حذف طلب تحقق صاحب الورشة');
    }

    /**
     * Determine whether the user can create or update
     * their shop profile.
     */
    public function saveOrUpdateProfile(User $user): bool
    {
        return $user->can('تعديل ملف الورشة');
    }

    /**
     * Determine whether the user can view
     * their shop profile.
     */
    public function viewShopProfile(User $user): bool
    {
        return $user->can('عرض ملف الورشة');
    }
}