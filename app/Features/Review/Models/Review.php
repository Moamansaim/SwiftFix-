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
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the shop that owns the review.
     */
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
}
