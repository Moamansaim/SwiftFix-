<?php

namespace App\Features\ShopOwner\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $casts = [
        'working_hours' => 'array',
    ];


    public function country()
    {
        return $this->belongsTo(
            Country::class,
            'country_id',
            'id'
        );
    }

    public function city()
    {
        return $this->belongsTo(
            City::class,
            'city_id',
            'id'
        );
    }

    public function districts()
    {
        return $this->belongsTo(
            Districts::class,
            'district_id',
            'id'
        );
    }
}