<?php

namespace App\Features\Country\Models;

use App\Features\City\Models\City;
use App\Features\ShopOwner\Models\Shop;
use App\Features\ShopOwner\Models\ShopOwnerVerification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Country Model
 *
 * Represents a country within the system.
 *
 * A country can have multiple:
 * - Shop owner verification records.
 * - Cities.
 * - Workshops (shops).
 *
 * Relationships:
 * - Country hasMany ShopOwnerVerification.
 * - Country hasMany City.
 * - Country hasMany Shop.
 *
 * @property int $id
 * @property string $name
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ShopOwnerVerification> $shopOwnerVerifications
 * @property-read \Illuminate\Database\Eloquent\Collection<int, City> $cities
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Shop> $shops
 */
class Country extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * This allows the country name to be assigned using
     * Laravel's mass assignment methods such as create()
     * and update().
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get the shop owner verification records associated
     * with this country.
     *
     * Relationship:
     *
     * Country 1 ---- * ShopOwnerVerification
     *
     * One country can have multiple shop owner verification
     * records.
     *
     * The foreign key is `country_id` in the
     * shop_owner_verifications table.
     *
     * @return HasMany<ShopOwnerVerification, $this>
     */
    public function shopOwnerVerifications(): HasMany
    {
        return $this->hasMany(
            ShopOwnerVerification::class,
            'country_id',
            'id'
        );
    }

    /**
     * Get the cities associated with this country.
     *
     * Relationship:
     *
     * Country 1 ---- * City
     *
     * One country can contain multiple cities,
     * while each city belongs to one country.
     *
     * The foreign key is `country_id` in the cities table.
     *
     * Example:
     *
     * $country->cities;
     *
     * @return HasMany<City, $this>
     */
    public function cities(): HasMany
    {
        return $this->hasMany(
            City::class,
            'country_id',
            'id'
        );
    }

    /**
     * Get the workshops associated with this country.
     *
     * Relationship:
     *
     * Country 1 ---- * Shop
     *
     * One country can contain multiple workshops,
     * while each workshop belongs to one country.
     *
     * The foreign key is `country_id` in the shops table.
     *
     * Example:
     *
     * $country->shops;
     *
     * @return HasMany<Shop, $this>
     */
    public function shops(): HasMany
    {
        return $this->hasMany(
            Shop::class,
            'country_id',
            'id'
        );
    }
}