<?php

namespace App\Features\Category\Models;

use App\Features\Product\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Category Model
 *
 * Represents a product category within the system.
 *
 * A category can contain multiple products through a
 * one-to-many relationship.
 *
 * @property int $id
 * @property string $category_name
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Product> $products
 */
class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * This allows the category name to be assigned using
     * Laravel's mass assignment methods.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_name',
    ];

    /**
     * Get the products associated with this category.
     *
     * Relationship:
     *
     * Category 1 ---- * Product
     *
     * This means:
     * - One category can contain many products.
     * - Each product belongs to one category.
     *
     * The foreign key used in the products table is
     * `category_id`, while the local key in the categories
     * table is `id`.
     *
     * Example:
     *
     * $category->products;
     *
     * @return HasMany<Product, $this>
     */
    public function products(): HasMany
    {
        return $this->hasMany(
            Product::class,
            'category_id',
            'id'
        );
    }
}