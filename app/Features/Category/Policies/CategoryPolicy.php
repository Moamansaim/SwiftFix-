<?php

namespace App\Features\Category\Policies;

use App\Features\Auth\Models\User;
use App\Features\Category\Models\Category;

class CategoryPolicy
{
    /**
     * Determine whether the user can retrieve all categories.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('عرض الفئات');
    }


    /**
     * Determine whether the user can create a category.
     */
    public function create(User $user): bool
    {
        return $user->can('إضافة فئة');
    }

    /**
     * Determine whether the user can update a category.
     */
    public function update(User $user, Category $category): bool
    {
        return $user->can('تعديل الفئة');
    }

    /**
     * Determine whether the user can delete a category.
     */
    public function delete(User $user, Category $category): bool
    {
        return $user->can('حذف الفئة');
    }
}