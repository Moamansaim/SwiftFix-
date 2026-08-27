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

    protected $fillable = ['device_model_name'];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(
            Brand::class,
            'brand_id',
            'id'
        );
    }

    public function partTypes(): HasMany
    {
        return $this->hasMany(
            Product::class,
            'device_model_id',
            'id'
        );
    }
}
