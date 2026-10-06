<?php

namespace App\Features\ShopProduct\Policies;

use App\Features\Auth\Models\User;
use App\Features\ShopProduct\Models\ShopProduct;

class ShopProductPolicy
{
    /**
     * Determine whether the user can view all shop products.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('عرض منتجات الورشة');
    }

    /**
     * Determine whether the user can create a shop product.
     */
    public function create(User $user): bool
    {
        return $user->can('إضافة منتج للورشة');
    }

    /**
     * Determine whether the user can update a shop product.
     */
    public function update(
        User $user,
        ShopProduct $shopProduct
    ): bool {
        return $user->can('تعديل منتج الورشة');
    }

    /**
     * Determine whether the user can delete a shop product.
     */
    public function delete(
        User $user,
        ShopProduct $shopProduct
    ): bool {
        return $user->can('حذف منتج الورشة');
    }
}