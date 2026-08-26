<?php

namespace App\Features\Country\Models;

use App\Features\City\Models\City;
use App\Features\ShopOwner\Models\Shop;
use App\Features\ShopOwner\Models\ShopOwnerVerification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Country extends Model
{
    use HasFactory;

    public function shopOwnerVerifications()
    {
        return $this->hasMany(
            ShopOwnerVerification::class,
            'country_id',
            'id'
        );
    }

    public function cities()
    {
        return $this->hasMany(
            City::class,
            'country_id',
            'id'
        );
    }

    public function shops()
    {
        return $this->hasMany(
            Shop::class,
            'country_id',
            'id'
        );
    }
}