<?php

namespace App\Features\City\Policies;

use App\Features\Auth\Models\User;
use App\Features\City\Models\City;

class CityPolicy
{
    /**
     * Display cities.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('عرض المدن');
    }

    /**
     * Create a new city.
     */
    public function create(User $user): bool
    {
        return $user->can('إضافة مدينة');
    }

    /**
     * Update a city.
     */
    public function update(User $user, City $city): bool
    {
        return $user->can('تعديل المدينة');
    }

    /**
     * Delete a city.
     */
    public function delete(User $user, City $city): bool
    {
        return $user->can('حذف المدينة');
    }
}