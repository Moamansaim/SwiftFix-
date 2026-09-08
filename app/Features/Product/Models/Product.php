<?php

namespace App\Features\Product\Models;

use App\Features\Category\Models\Category;
use App\Features\DeviceModel\Models\DeviceModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'category_id',
    ];

    /**
     * Get the category associated with the product.
     *
     * @return BelongsTo
     *
     * @hint Defines a many-to-one relationship between the product
     *        and its category using the category_id foreign key.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(
            Category::class,
            'category_id',
            'id'
        );
    }
}