<?php

namespace App\Features\DeviceModel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceModel extends Model
{
    protected $fillable = ['device_model_name'];

    use HasFactory;
}