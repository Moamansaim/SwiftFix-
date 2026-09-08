<?php

namespace App\Features\CustomerRepairRequests\Models;

use App\Features\Auth\Models\User;
use App\Features\DeviceModel\Models\DeviceModel;
use App\Features\Services\Models\Service;
use App\Features\ShopOwner\Models\Shop;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Represents a customer repair request.
 *
 * Stores the repair request submitted by a customer
 * for a specific shop, device model, and service.
 */
class CustomerRepairRequest extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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

    /**
     * Get the customer who submitted the repair request.
     *
     * @return BelongsTo
     *
     * @hint Each repair request belongs to one user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the shop that receives the repair request.
     *
     * @return BelongsTo
     *
     * @hint Each repair request belongs to one shop.
     */
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Get the device model related to the repair request.
     *
     * @return BelongsTo
     *
     * @hint Each repair request belongs to one device model.
     */
    public function deviceModel(): BelongsTo
    {
        return $this->belongsTo(DeviceModel::class);
    }

    /**
     * Get the service requested by the customer.
     *
     * @return BelongsTo
     *
     * @hint Each repair request belongs to one service.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}