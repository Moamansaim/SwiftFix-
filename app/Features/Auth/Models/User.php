<?php

namespace App\Features\Auth\Models;

// استيرادات النماذج الأخرى (للعلاقات)
use App\Models\Workshop;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Conversation;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles; // ✨ إضافة جديدة: لدعم الأدوار (Spatie)

// استيرادات صاحبك الأصلية
use App\Features\Auth\Notifications\VerifyEmailNotification;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    // ✨ تعديل 1: إضافة SoftDeletes و HasRoles
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'email',
        'password',
        'city',   // ✨ تعديل 2: إضافة المدينة
        'status', // ✨ تعديل 3: إضافة الحالة (active/suspended)
    ];

    /**
     * The attributes that should be hidden for serialization.
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
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function newFactory()
    {
        return UserFactory::new();
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmailNotification);
    }

    public function markEmailAsVerified(): bool
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    // --- ✨ إضافات SwiftFix (Helper Methods & Relationships) ---

    // دوال مساعدة للتحقق من الأدوار والحالة
    public function isAdmin(): bool     { return $this->hasRole('admin'); }
    public function isOwner(): bool     { return $this->hasRole('workshop_owner'); }
    public function isCustomer(): bool  { return $this->hasRole('customer'); }
    public function isSuspended(): bool { return $this->status === 'suspended'; }

    // العلاقات مع نماذج الأعمال
    public function workshop(): HasOne       { return $this->hasOne(Workshop::class); }
    public function bookings(): HasMany      { return $this->hasMany(Booking::class, 'customer_id'); }
    public function reviews(): HasMany       { return $this->hasMany(Review::class, 'customer_id'); }
    
    // المحادثات التي بدأها الزبون
    public function conversations(): HasMany { return $this->hasMany(Conversation::class, 'customer_id'); }
    
    // المحادثات التي يشارك فيها كصاحب ورشة
    public function ownerConversations(): HasMany { return $this->hasMany(Conversation::class, 'workshop_owner_id'); }
}