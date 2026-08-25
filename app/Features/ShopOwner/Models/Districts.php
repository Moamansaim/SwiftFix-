<?php

namespace App\Features\ShopOwner\Models;


use App\Features\ShopOwner\Models\City;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Districts extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'city_id'];
    
    public function city()
    {
        return $this->belongsTo(
            City::class,
            'city_id',
            'id'
        );
    }
}