<?php

namespace App\Features\Country\Policies;

use App\Features\Auth\Models\User;
use App\Features\Country\Models\Country;

class CountryPolicy
{
    /**
     * Determine whether the user can view all countries.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('عرض الدول');
    }

    /**
     * Determine whether the user can create a country.
     */
    public function create(User $user): bool
    {
        return $user->can('إضافة دولة');
    }

    /**
     * Determine whether the user can update a country.
     */
    public function update(
        User $user,
        Country $country
    ): bool {
        return $user->can('تعديل الدولة');
    }

    /**
     * Determine whether the user can delete a country.
     */
    public function delete(
        User $user,
        Country $country
    ): bool {
        return $user->can('حذف الدولة');
    }
}