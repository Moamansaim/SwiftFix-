<?php

namespace App\Features\DeviceModel\Models;

use App\Features\Brand\Models\Brand;
use App\Features\Product\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeviceModel extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     *
     * @hint Defines the fields that can be assigned using
     *        mass assignment when creating or updating a device model.
     */
    protected $fillable = [
        'device_model_name',
        'brand_id',
    ];

    /**
     * Get the brand associated with the device model.
     *
     * @return BelongsTo
     *
     * @hint Each device model belongs to one brand.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(
            Brand::class,
            'brand_id',
            'id'
        );
    }

    /**
     * Get the products associated with the device model.
     *
     * @return HasMany
     *
     * @hint Each device model can have multiple products
     *        associated with it.
     */
    public function partTypes(): HasMany
    {
        return $this->hasMany(
            Product::class,
            'device_model_id',
            'id'
        );
    }
}