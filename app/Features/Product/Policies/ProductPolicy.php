<?php

namespace App\Features\Product\Policies;

use App\Features\Auth\Models\User;
use App\Features\Product\Models\Product;

class ProductPolicy
{
    /**
     * Determine whether the user can view any products.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('عرض المنتجات');
    }


    /**
     * Determine whether the user can create a product.
     */
    public function create(User $user): bool
    {
        return $user->can('إضافة منتج');
    }

    /**
     * Determine whether the user can update the product.
     */
    public function update(
        User $user,
        Product $product
    ): bool {
        return $user->can('تعديل منتج');
    }

    /**
     * Determine whether the user can delete the product.
     */
    public function delete(
        User $user,
        Product $product
    ): bool {
        return $user->can('حذف منتج');
    }
}