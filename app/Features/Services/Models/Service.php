<?php

namespace App\Features\Services\Models;

use App\Features\ShopOwner\Models\Shop;
use App\Features\ShopOwner\Models\ShopOwnerVerification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Service extends Model
{
    use HasFactory;

    public function shopOwnerVerifications()
    {
        return $this->belongsToMany(
            ShopOwnerVerification::class,
            'shop_owner_verification_service',
            'service_id',
            'shop_owner_verification_id'
        );
    }

    public function shops()
    {
        return $this->belongsToMany(
            Shop::class,
            'service_shop',
            'service_id',
            'shop_id',
        );
    }
}