<?php

namespace App\Features\DeviceModel\Policies;

use App\Features\Auth\Models\User;
use App\Features\DeviceModel\Models\DeviceModel;

class DeviceModelPolicy
{
    /**
     * Determine whether the user can view any device models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('عرض الأجهزة');
    }
   

    /**
     * Determine whether the user can create a device model.
     */
    public function create(User $user): bool
    {
        return $user->can('إضافة جهاز');
    }

    /**
     * Determine whether the user can update the device model.
     */
    public function update(
        User $user,
        DeviceModel $deviceModel
    ): bool {
        return $user->can('تعديل جهاز');
    }

    /**
     * Determine whether the user can delete the device model.
     */
    public function delete(
        User $user,
        DeviceModel $deviceModel
    ): bool {
        return $user->can('حذف جهاز');
    }
}