<?php

namespace App\Features\Brand\Models;

use App\Features\DeviceModel\Models\DeviceModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = ['brand_name'];

    public function deviceModels(): HasMany
    {
        return $this->hasMany(
            DeviceModel::class,
            'brand_id',
            'id'
        );
    }
}
