<?php

namespace App\Features\CustomerRepairRequest\Models;

use App\Features\Auth\Models\User;
use App\Features\DeviceModel\Models\DeviceModel;
use App\Features\Services\Models\Service;
use App\Features\ShopOwner\Models\Shop;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerRepairRequest extends Model
{
    protected $fillable = [
        'user_id',
        'shop_id',
        'device_model_id',
        'service_id',
        'description',
        'image',
        'status',
        'phone_number',
        'address',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function deviceModel(): BelongsTo
    {
        return $this->belongsTo(DeviceModel::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}