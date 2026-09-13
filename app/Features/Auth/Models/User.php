<?php

namespace App\Features\Auth\Models;

use Spatie\Permission\Traits\HasRoles;

use App\Features\Auth\Notifications\VerifyEmailNotification;
use App\Features\Favorite\Models\Favorite;
use App\Features\ShopOwner\Models\Shop;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * User model.
 *
 * Represents users registered on the application and provides
 * authentication, email verification, notifications, and
 * relationships with shops and favorites.
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * These attributes can be assigned using mass assignment
     * when creating or updating a user.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'email',
        'code',
        'code_expires_at',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * These attributes will not be included when the user model
     * is converted to an array or JSON response.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * Defines how specific database attributes should be converted
     * when retrieved from or stored in the database.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Send the email verification notification.
     *
     * Sends the custom email verification notification
     * to the user's registered email address.
     *
     * @return void
     */
    public function sendEmailVerificationNotification(): void
    {
        // Send the custom email verification notification.
        $this->notify(new VerifyEmailNotification);
    }

    /**
     * Mark the user's email address as verified.
     *
     * Updates the email_verified_at column with the current
     * timestamp and saves the changes to the database.
     *
     * @return bool True if the user was successfully updated.
     */
    public function markEmailAsVerified(): bool
    {
        // Set the email verification timestamp and save the user.
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    /**
     * Get the shop owned by the user.
     *
     * Defines a one-to-one relationship between the user
     * and their workshop/shop profile.
     *
     * @return HasOne
     */
    public function shop(): HasOne
    {
        // A user can have one shop.
        return $this->hasOne(
            Shop::class,
            'user_id',
            'id'
        );
    }

    /**
     * Get the user's favorites.
     *
     * Defines a one-to-many relationship between the user
     * and their favorite records.
     *
     * @return HasMany
     */
    public function favorites(): HasMany
    {
        // A user can have multiple favorite records.
        return $this->hasMany(
            Favorite::class,
            'user_id',
            'id'
        );
    }
}