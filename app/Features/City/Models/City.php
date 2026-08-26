<?php

namespace App\Features\City\Models;

use App\Features\Country\Models\Country;
use App\Features\Districts\Models\Districts;
use App\Features\ShopOwner\Models\Shop;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class City extends Model
{
    use HasFactory;

    public function country()
    {
        return $this->belongsTo(
            Country::class,
            'country_id',
            'id'
        );
    }

    public function districts()
    {
        return $this->hasMany(
            Districts::class,
            'city_id',
            'id'
        );
    }

    public function shops()
    {
        return $this->hasMany(
            Shop::class,
            'city_id',
            'id'
        );
    }
}