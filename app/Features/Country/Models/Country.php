<?php

namespace App\Features\Country\Models;

use App\Features\City\Models\City;
use App\Features\ShopOwner\Models\Shop;
use App\Features\ShopOwner\Models\ShopOwnerVerification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    use HasFactory;

    public function shopOwnerVerifications(): HasMany
    {
        return $this->hasMany(
            ShopOwnerVerification::class,
            'country_id',
            'id'
        );
    }

    public function cities(): HasMany
    {
        return $this->hasMany(
            City::class,
            'country_id',
            'id'
        );
    }

    public function shops(): HasMany
    {
        return $this->hasMany(
            Shop::class,
            'country_id',
            'id'
        );
    }
}
