<?php

namespace App\Features\UserSettings\Models;

use Illuminate\Database\Eloquent\Model;

class AccountDeletionReason extends Model
{
    protected $fillable = [
        'reason',
        'deletion_count',
        'is_active',
    ];
}