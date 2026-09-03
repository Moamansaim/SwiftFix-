<?php

namespace App\Features\ShopOwner\Models;

use App\Features\Auth\Models\User;
use App\Features\City\Models\City;
use App\Features\Country\Models\Country;
use App\Features\Favorite\Models\Favorite;
use App\Features\FeatureShop\Models\FeatureShop;
use App\Features\Services\Models\Service;
use App\Features\ShopProduct\Models\ShopProduct;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shop extends Model
{
    protected $fillable = [
        'user_id',
        'shop_name',
        'description',
        'cover_image',
        'country_id',
        'city_id',
        'district',
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

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(
            Service::class,
            'service_shop',
            'shop_id',
            'service_id',
        )->withPivot('price');
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(
            Favorite::class,
            'shop_id',
            'id'
        );
    }

    public function shopProducts(): HasMany
    {
        return $this->hasMany(
            ShopProduct::class,
            'shop_id',
            'id'
        );
    }

    public function featuresShop(): HasMany
    {
        return $this->hasMany(
            FeatureShop::class,
            'shop_id',
            'id'
        );
    }


    /**
     * Filter shops by name.
     */
    public function scopeByName(Builder $query, ?string $name): Builder
    {
        return $query->when(
            $name,
            fn($query) => $query->where(
                'shop_name',
                'like',
                "%{$name}%"
            )
        );
    }

    /**
     * Filter shops by country name.
     */
    public function scopeByCountry(Builder $query, ?string $countryName): Builder
    {
        return $query->when(
            $countryName,
            fn($query) => $query->whereHas(
                'country',
                fn($query) => $query->where(
                    'name',
                    'like',
                    "%{$countryName}%"
                )
            )
        );
    }

    /**
     * Filter shops by city name.
     */
    public function scopeByCity(Builder $query, ?string $cityName): Builder
    {
        return $query->when(
            $cityName,
            fn($query) => $query->whereHas(
                'city',
                fn($query) => $query->where(
                    'name',
                    'like',
                    "%{$cityName}%"
                )
            )
        );
    }

    /**
     * Filter shops by district.
     */
    public function scopeByDistrict(Builder $query, ?string $district): Builder
    {
        return $query->when(
            $district,
            fn($query) => $query->where(
                'district',
                'like',
                "%{$district}%"
            )
        );
    }

    /**
     * Filter shops by street.
     */
    public function scopeByStreet(Builder $query, ?string $street): Builder
    {
        return $query->when(
            $street,
            fn($query) => $query->where(
                'street',
                'like',
                "%{$street}%"
            )
        );
    }

    /**
     * Filter shops by status.
     */
    public function scopeByStatus(Builder $query, ?string $status): Builder
    {
        return $query->when(
            $status,
            fn($query) => $query->where(
                'status',
                $status
            )
        );
    }

    /**
     * Filter shops by service.
     */
    public function scopeByService(Builder $query, ?int $serviceId): Builder
    {
        return $query->when(
            $serviceId,
            fn($query) => $query->whereHas(
                'services',
                fn($query) => $query->where(
                    'services.id',
                    $serviceId
                )
            )
        );
    }

    /**
     * Filter shops by service price range.
     */
    public function scopeByPrice(
        Builder $query,
        $minPrice = null,
        $maxPrice = null
    ): Builder {
        return $query->when(
            $minPrice !== null || $maxPrice !== null,
            function ($query) use ($minPrice, $maxPrice) {

                $query->whereHas(
                    'services',
                    function ($query) use ($minPrice, $maxPrice) {

                        $query->when(
                            $minPrice !== null,
                            fn($query) => $query->wherePivot(
                                'price',
                                '>=',
                                $minPrice
                            )
                        );

                        $query->when(
                            $maxPrice !== null,
                            fn($query) => $query->wherePivot(
                                'price',
                                '<=',
                                $maxPrice
                            )
                        );
                    }
                );
            }
        );
    }
}