<?php

namespace App\Features\ShopOwner\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Service extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public $timestamps = false;   // 👈 الجدول ما فيه created_at/updated_at

    public function shopOwnerVerifications()
    {
        return $this->belongsToMany(
            ShopOwnerVerification::class,
            'shop_owner_verification_service',
            'service_id',
            'shop_owner_verification_id'
        );
    }
}