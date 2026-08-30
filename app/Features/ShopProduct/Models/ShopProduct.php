<?php

namespace App\Features\ShopProduct\Models;

use App\Features\Brand\Models\Brand;
use App\Features\Category\Models\Category;
use App\Features\DeviceModel\Models\DeviceModel;
use App\Features\ShopOwner\Models\Shop;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShopProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'product_id',
        'device_model_id',
        'quantity',
        'price',
        'image',
        'description',
        'status',
    ];

    /**
     * The shop that owns the product.
     */
    public function shop(): BelongsTo
    {
        return $this->belongsTo(
            Shop::class,
            'shop_id',
            'id'
        );
    }

    /**
     * The category of the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(
            Category::class,
            'category_id',
            'id'
        );
    }

    /**
     * The brand of the product.
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
     * The device model related to the product.
     */
    public function deviceModel(): BelongsTo
    {
        return $this->belongsTo(
            DeviceModel::class,
            'device_model_id',
            'id'
        );
    }
}
