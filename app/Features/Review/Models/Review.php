<?php

namespace App\Features\Review\Models;

use App\Features\Auth\Models\User;
use App\Features\ShopOwner\Models\Shop;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'shop_id',
        'rating',
        'comment',
        'shop_reply',
        'replied_at',
    ];

    protected $casts = [
        'rating' => 'integer',
        'replied_at' => 'datetime',
    ];

    /**
     * Get the user who created the review.
     *
     * @return BelongsTo
     *
     * @hint Defines a many-to-one relationship between the review
     *        and the user who created it through the user_id foreign key.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the shop that owns the review.
     *
     * @return BelongsTo
     *
     * @hint Defines a many-to-one relationship between the review
     *        and the shop being reviewed through the shop_id foreign key.
     */
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
}