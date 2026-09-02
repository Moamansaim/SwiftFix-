<?php

namespace App\Features\Brand\Models;

use App\Features\DeviceModel\Models\DeviceModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Brand Model
 *
 * Represents a device brand within the system.
 *
 * A brand can have multiple device models through
 * a one-to-many relationship.
 *
 * @property int $id
 * @property string $brand_name
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, DeviceModel> $deviceModels
 */
class Brand extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * This allows the brand name to be assigned using
     * Laravel's mass assignment methods such as:
     *
     * Brand::create([
     *     'brand_name' => 'Apple',
     * ]);
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'brand_name',
    ];

    /**
     * Get the device models associated with this brand.
     *
     * Relationship:
     *
     * Brand 1 ---- * DeviceModel
     *
     * This means:
     * - One brand can have many device models.
     * - Each device model belongs to one brand.
     *
     * The foreign key used in the device_models table is
     * `brand_id`, while the local key in the brands table is `id`.
     *
     * Example:
     *
     * $brand->deviceModels;
     *
     * @return HasMany<DeviceModel, $this>
     */
    public function deviceModels(): HasMany
    {
        return $this->hasMany(
            DeviceModel::class,
            'brand_id',
            'id'
        );
    }
}