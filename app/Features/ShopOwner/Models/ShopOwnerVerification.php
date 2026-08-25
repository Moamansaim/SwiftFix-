<?php

namespace App\Features\ShopOwner\Models;

use App\Features\Auth\Models\User;
use App\Features\ShopOwner\Models\Country;
use App\Features\ShopOwner\Models\Service;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class ShopOwnerVerification extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'national_id_image',
        'country_id',
        'notes',
        'status',
        'reviewed_by',
        'reviewed_at',
    ];

    public function services()
    {
        return $this->belongsToMany(
            Service::class,
            'shop_owner_verification_service',
            'shop_owner_verification_id',
            'service_id',
        );
    }

    public function country()
    {
        return $this->belongsTo(
            Country::class,
            'country_id',
            'id'
        );
    }

    protected function casts(): array
    {
        return ['reviewed_at' => 'datetime'];
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}