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

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            Category::class,
            'category_id',
            'id'
        );
    }
}