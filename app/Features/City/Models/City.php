<?php

namespace App\Features\City\Models;

use App\Features\Country\Models\Country;
use App\Features\ShopOwner\Models\Shop;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    use HasFactory;

    public function country(): BelongsTo
    {
        return $this->belongsTo(
            Country::class,
            'country_id',
            'id'
        );
    }

    public function shops(): HasMany
    {
        return $this->hasMany(
            Shop::class,
            'city_id',
            'id'
        );
    }
}
