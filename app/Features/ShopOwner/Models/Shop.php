<?php

namespace App\Features\ShopOwner\Models;

use App\Features\Auth\Models\User;
use App\Features\City\Models\City;
use App\Features\Country\Models\Country;
use App\Features\Districts\Models\Districts;
use App\Features\Services\Models\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Shop extends Model
{
    protected $fillable = [
        'user_id',
        'shop_name',
        'description',
        'cover_image',
        'country_id',
        'city_id',
        'district_id',
        'street',
        'latitude',
        'longitude',
        'working_hours',
    ];

    protected $casts = [
        'working_hours' => 'array',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id',
            'id'
        );
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(
            Country::class,
            'country_id',
            'id'
        );
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(
            City::class,
            'city_id',
            'id'
        );
    }

    public function districts(): BelongsTo
    {
        return $this->belongsTo(
            Districts::class,
            'district_id',
            'id'
        );
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(
            Service::class,
            'service_shop',
            'shop_id',
            'service_id',
        );
    }
}