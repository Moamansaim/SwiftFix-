<?php

namespace App\Features\Complaint\Models;

use App\Features\Auth\Models\User;
use App\Features\ShopOwner\Models\Shop;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shop_id',
        'subject',
        'message',
        'status',
        'admin_reply',
        'replied_at',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
    ];

    /**
     * Customer who submitted the complaint.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Shop related to the complaint.
     */
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
}