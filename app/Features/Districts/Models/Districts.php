<?php

namespace App\Features\Districts\Models;

use App\Features\City\Models\City;
use App\Features\ShopOwner\Models\Shop;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Districts extends Model
{
    use HasFactory;

    public function city(): BelongsTo
    {
        return $this->belongsTo(
            City::class,
            'city_id',
            'id'
        );
    }

    public function shops(): HasMany
    {
        return $this->hasMany(
            Shop::class,
            'district_id',
            'id'
        );
    }
}
