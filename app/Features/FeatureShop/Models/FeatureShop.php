<?php

namespace App\Features\FeatureShop\Models;

use App\Features\ShopOwner\Models\Shop;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeatureShop extends Model
{
    use HasFactory;

    /**
     * The database table associated with the model.
     *
     * @var string
     *
     * @hint Specifies the custom database table used by the FeatureShop model.
     */
    public $table = 'shop_features';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     *
     * @hint Defines the fields that can be assigned using
     *        mass assignment when creating or updating a shop feature.
     */
    protected $fillable = [
        'shop_id',
        'feature',
    ];

    /**
     * Get the shop associated with the feature.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *
     * @hint Each shop feature belongs to one shop through the shop_id foreign key.
     */
    public function shops()
    {
        return $this->belongsTo(
            Shop::class,
            'shop_id',
            'id'
        );
    }
}