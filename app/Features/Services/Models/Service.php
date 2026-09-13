<?php

namespace App\Features\Services\Models;

use App\Features\ShopOwner\Models\Shop;
use App\Features\ShopOwner\Models\ShopOwnerVerification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['service_name'];

    public function shopOwnerVerifications(): BelongsToMany
    {
        return $this->belongsToMany(
            ShopOwnerVerification::class,
            'shop_owner_verification_service',
            'service_id',
            'shop_owner_verification_id'
        );
    }

    public function shops(): BelongsToMany
    {
        return $this->belongsToMany(
            Shop::class,
            'service_shop',
            'service_id',
            'shop_id',
        )->withPivot('price');
    }
}
