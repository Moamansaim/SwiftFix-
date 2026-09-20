<?php

namespace App\Features\ShopOwner\Models;

use App\Features\Auth\Models\User;
use App\Features\Country\Models\Country;
use App\Features\Services\Models\Service;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;   // ← الاستيراد



class ShopOwnerVerification extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'national_id_image',
        'commercial_record_image',
        'country_id',
        'service_ids',
        'status',
        'reviewed_by',
        'reviewed_at',
        'notes',
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

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'reviewed_by',
            'id'
        );
    }
}