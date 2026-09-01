<?php

namespace App\Features\FeatureShop\Models;

use App\Features\ShopOwner\Models\Shop;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeatureShop extends Model
{
    use HasFactory;

    public function shops()
    {
        return $this->belongsTo(
            Shop::class,
            'shop_id',
            'id'
        );
    }
}
