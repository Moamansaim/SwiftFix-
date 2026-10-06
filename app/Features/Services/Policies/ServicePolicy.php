<?php

namespace App\Features\Services\Policies;

use App\Features\Auth\Models\User;
use App\Features\Services\Models\Service;

class ServicePolicy
{
    /**
     * Determine whether the user can view all services.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('عرض الخدمات');
    }


    /**
     * Determine whether the user can create a service.
     */
    public function create(User $user): bool
    {
        return $user->can('إضافة خدمة');
    }

    /**
     * Determine whether the user can update a service.
     */
    public function update(
        User $user,
        Service $service
    ): bool {
        return $user->can('تعديل خدمة');
    }

    /**
     * Determine whether the user can delete a service.
     */
    public function delete(
        User $user,
        Service $service
    ): bool {
        return $user->can('حذف خدمة');
    }
}