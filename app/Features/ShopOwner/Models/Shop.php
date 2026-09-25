<?php

namespace App\Features\ShopOwner\Models;

use App\Features\Auth\Models\User;
use App\Features\City\Models\City;
use App\Features\Complaint\Models\Complaint;
use App\Features\Country\Models\Country;
use App\Features\CustomerRepairRequests\Models\CustomerRepairRequest;
use App\Features\Favorite\Models\Favorite;
use App\Features\FeatureShop\Models\FeatureShop;
use App\Features\Review\Models\Review;
use App\Features\Services\Models\Service;
use App\Features\ShopProduct\Models\ShopProduct;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shop extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'shop_name',
        'description',
        'cover_image',
        'commercial_record_image',
        'country_id',
        'city_id',
        'district',
        'street',
        'latitude',
        'longitude',
        'status',
        'working_hours',
        'is_verified',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'working_hours' => 'array',
    ];

    /**
     * Get the user who owns the shop.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id',
            'id'
        );
    }

    /**
     * Get the country associated with the shop.
     *
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(
            Country::class,
            'country_id',
            'id'
        );
    }

    /**
     * Get the city associated with the shop.
     *
     * @return BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(
            City::class,
            'city_id',
            'id'
        );
    }

    /**
     * Get all services provided by the shop.
     *
     * The relationship uses the service_shop pivot table
     * and also retrieves the service price stored in the pivot.
     *
     * @return BelongsToMany
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(
            Service::class,
            'service_shop',
            'shop_id',
            'service_id',
        )->withPivot('price');
    }

    /**
     * Get all favorites associated with the shop.
     *
     * @return HasMany
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(
            Favorite::class,
            'shop_id',
            'id'
        );
    }

    /**
     * Get all products available in the shop.
     *
     * ShopProduct represents the inventory relationship
     * between the shop and its products.
     *
     * @return HasMany
     */
    public function shopProducts(): HasMany
    {
        return $this->hasMany(
            ShopProduct::class,
            'shop_id',
            'id'
        );
    }

    /**
     * Get all features associated with the shop.
     *
     * @return HasMany
     */
    public function featuresShop(): HasMany
    {
        return $this->hasMany(
            FeatureShop::class,
            'shop_id',
            'id'
        );
    }

    /**
     * Get all reviews associated with the shop.
     *
     * A shop can have multiple reviews submitted by customers.
     *
     * @return HasMany
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(
            Review::class,
            'shop_id',
            'id'
        );
    }


    public function customerRepairRequests(): HasMany
    {
        return $this->hasMany(CustomerRepairRequest::class);
    }


    /**
     * Complaints submitted against this shop.
     */
    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }

    /**
     * Filter shops by name.
     *
     * Performs a partial search using the shop name.
     *
     * @param Builder $query
     * @param string|null $name
     *
     * @return Builder
     */
    public function scopeByName(
        Builder $query,
        ?string $name
    ): Builder {
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
     * Filter shops by city name.
     *
     * Searches for shops that belong to a city
     * matching the provided name.
     *
     * @param Builder $query
     * @param string|null $cityName
     *
     * @return Builder
     */
    public function scopeByCity(
        Builder $query,
        ?string $cityName
    ): Builder {
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
     * Filter shops by status.
     *
     * @param Builder $query
     * @param string|null $status
     *
     * @return Builder
     */
    public function scopeByStatus(
        Builder $query,
        ?string $status
    ): Builder {
        return $query->when(
            $status,
            fn(Builder $query) => $query->where('status', $status)
        );
    }

    /**
     * Filter shops by service.
     *
     * Returns shops that provide the specified service.
     *
     * @param Builder $query
     * @param int|null $serviceId
     *
     * @return Builder
     */
    public function scopeByService(
        Builder $query,
        ?int $serviceId
    ): Builder {
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
     * Filter shops by verification status.
     *
     * Accepts only 0 or 1 as the verification value.
     *
     * @param Builder $query
     * @param int|null $isVerified
     *
     * @return Builder
     */
    public function scopeByVerification(
        Builder $query,
        ?int $isVerified
    ): Builder {
        return $query->when(
            $isVerified !== null,
            fn(Builder $query) => $query->where(
                'is_verified',
                $isVerified
            )
        );
    }

    /**
     * Filter shops by service price.
     *
     * Returns shops that have at least one service
     * with the specified price.
     *
     * @param Builder $query
     * @param float|int|null $price
     *
     * @return Builder
     */
    public function scopeByPrice(
        Builder $query,
        float|int|null $price = null
    ): Builder {
        return $query->when(
            $price !== null,
            function (Builder $query) use ($price) {
                $query->whereHas(
                    'services',
                    function (Builder $query) use ($price) {
                        $query->where(
                            'service_shop.price',
                            '=',
                            $price
                        );
                    }
                );
            }
        );
    }

    /**
     * Filter shops by spare part name.
     *
     * Searches for shops that have a spare part matching
     * the provided product name in their inventory.
     *
     * The search goes through the following relationships:
     *
     * Shop
     *   -> ShopProduct
     *      -> Product
     *
     * A partial name match is supported.
     *
     * @param Builder $query
     * @param string|null $sparePart
     *
     * @return Builder
     */
    public function scopeBySparePart(
        Builder $query,
        ?string $sparePart
    ): Builder {
        return $query->when(
            $sparePart,
            fn($query) => $query->whereHas(
                'shopProducts.product',
                fn($query) => $query->where(
                    'product_name',
                    'like',
                    "%{$sparePart}%"
                )
            )
        );
    }

    /**
     * Filter shops by minimum average rating.
     *
     * Returns shops whose average customer rating
     * is greater than or equal to the specified rating.
     *
     * For example:
     *
     * rating = 4
     *
     * Returns shops with an average rating of 4 or higher.
     *
     * @param Builder $query
     * @param float|null $rating
     *
     * @return Builder
     */
    public function scopeByRating(
        Builder $query,
        ?int $rating
    ): Builder {
        return $query->when(
            $rating !== null,
            function (Builder $query) use ($rating) {
                return $query->whereRaw(
                    '(
                    SELECT AVG(reviews.rating)
                    FROM reviews
                    WHERE reviews.shop_id = shops.id
                ) >= ?',
                    [$rating]
                );
            }
        );
    }
}