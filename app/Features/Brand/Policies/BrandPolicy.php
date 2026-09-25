<?php

namespace App\Features\Brand\Policies;

use App\Features\Auth\Models\User;
use App\Features\Brand\Models\Brand;

class BrandPolicy
{
    /**
     * Determine whether the user can view all brands.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('عرض العلامات التجارية');
    }

    /**
     * Determine whether the user can view a brand.
     */
    public function view(User $user, Brand $brand): bool
    {
        return $user->can('عرض العلامة التجارية');
    }

    /**
     * Determine whether the user can create a brand.
     */
    public function create(User $user): bool
    {
        return $user->can('إضافة علامة تجارية');
    }

    /**
     * Determine whether the user can update a brand.
     */
    public function update(User $user, Brand $brand): bool
    {
        return $user->can('تعديل علامة تجارية');
    }

    /**
     * Determine whether the user can delete a brand.
     */
    public function delete(User $user, Brand $brand): bool
    {
        return $user->can('حذف علامة تجارية');
    }
}