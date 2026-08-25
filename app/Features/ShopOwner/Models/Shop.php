<?php

namespace App\Features\ShopOwner\Models;

use App\Features\Auth\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'shop_name', 'description', 'cover_image',
        'country_id', 'city_id', 'district_id', 'street',
        'latitude', 'longitude', 'working_hours', 'status',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'rating_average' => 'decimal:2',
            'rating_count' => 'integer',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(Districts::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'service_shop');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    // ADR-010: 'bloked' typo in migration — will be fixed by a future migration.
    public function isBlocked(): bool
    {
        return $this->status === 'bloked';
    }
}