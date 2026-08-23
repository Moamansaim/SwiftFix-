<?php

namespace App\Features\ShopOwner\Models;

use App\Features\ShopOwner\Models\Country;
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
}