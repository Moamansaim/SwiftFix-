<?php

namespace App\Features\ShopOwner\Models;


use App\Features\ShopOwner\Models\City;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Districts extends Model
{
    use HasFactory;

    public function city()
    {
        return $this->belongsTo(
            City::class,
            'city_id',
            'id'
        );
    }

    public function shops()
    {
        return $this->hasMany(
            Shop::class,
            'district_id',
            'id'
        );
    }
}