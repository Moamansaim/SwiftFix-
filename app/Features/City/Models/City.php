<?php

namespace App\Features\City\Models;

use App\Features\Country\Models\Country;
use App\Features\ShopOwner\Models\Shop;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * City Model
 *
 * Represents a city within the system.
 *
 * A city belongs to one country and can contain multiple
 * workshops (shops).
 *
 * Relationships:
 * - City belongsTo Country.
 * - City hasMany Shop.
 *
 * @property int $id
 * @property int $country_id
 * @property string $name
 *
 * @property-read Country $country
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Shop> $shops
 */
class City extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * These fields can be assigned using Laravel's
     * mass assignment methods such as create() and update().
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'country_id',
        'name',
    ];

    /**
     * Get the country that this city belongs to.
     *
     * Relationship:
     *
     * City * ---- 1 Country
     *
     * Each city belongs to one country, while a country
     * can contain multiple cities.
     *
     * The foreign key is `country_id` in the cities table,
     * and the referenced key is `id` in the countries table.
     *
     * Example:
     *
     * $city->country;
     *
     * @return BelongsTo<Country, $this>
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
     * Get the workshops associated with this city.
     *
     * Relationship:
     *
     * City 1 ---- * Shop
     *
     * One city can contain multiple workshops,
     * while each workshop belongs to one city.
     *
     * The foreign key is `city_id` in the shops table,
     * and the local key is `id` in the cities table.
     *
     * Example:
     *
     * $city->shops;
     *
     * @return HasMany<Shop, $this>
     */
    public function shops(): HasMany
    {
        return $this->hasMany(
            Shop::class,
            'city_id',
            'id'
        );
    }
}