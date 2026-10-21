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


    /**
     * Get the shop owner verifications associated with the service.
     *
     * @return BelongsToMany
     * @hint Retrieves all shop owner verification requests linked to this
     *       service through the shop_owner_verification_service pivot table.
     */
    public function shopOwnerVerifications(): BelongsToMany
    {
        return $this->belongsToMany(
            ShopOwnerVerification::class,
            'shop_owner_verification_service',
            'service_id',
            'shop_owner_verification_id'
        );
    }

    /**
     * Get the shops that provide this service.
     *
     * @return BelongsToMany
     * @hint Retrieves all shops offering this service through the service_shop
     *       pivot table, including the service price for each shop.
     */
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