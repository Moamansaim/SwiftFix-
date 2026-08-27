<?php

namespace App\Features\Category\Models;

use App\Features\Product\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_name',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(
            Product::class,
            'category_id',
            'id'
        );
    }
}
