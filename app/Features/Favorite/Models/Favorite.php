<?php

namespace App\Features\Favorite\Models;

use App\Features\Auth\Models\User;
use App\Features\ShopOwner\Models\Shop;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favorite extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     *
     * @hint Defines the fields that can be assigned using
     *        mass assignment when creating a favorite record.
     */
    protected $fillable = [
        'user_id',
        'shop_id',
    ];

    /**
     * Favorite belongs to a user.
     *
     * @return BelongsTo
     *
     * @hint Each favorite record belongs to one user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Favorite belongs to a shop.
     *
     * @return BelongsTo
     *
     * @hint Each favorite record belongs to one shop.
     */
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
}