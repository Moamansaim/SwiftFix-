<?php

namespace App\Features\FeatureShop\Policies;

use App\Features\Auth\Models\User;
use App\Features\FeatureShop\Models\FeatureShop;

class FeatureShopPolicy
{
    /**
     * Determine whether the user can view any feature shops.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('عرض ميزات المتجر');
    }
 

    /**
     * Determine whether the user can create a feature shop.
     */
    public function create(User $user): bool
    {
        return $user->can('إضافة ميزة للمتجر');
    }

    /**
     * Determine whether the user can update the feature shop.
     */
    public function update(
        User $user,
        FeatureShop $featureShop
    ): bool {
        return $user->can('تعديل ميزة للمتجر');
    }

    /**
     * Determine whether the user can delete the feature shop.
     */
    public function delete(
        User $user,
        FeatureShop $featureShop
    ): bool {
        return $user->can('حذف ميزة للمتجر');
    }
}