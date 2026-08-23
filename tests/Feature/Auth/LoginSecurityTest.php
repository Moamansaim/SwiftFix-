<?php

namespace Tests\Feature\Auth;

use App\Features\Auth\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class LoginSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::create(['name' => 'customer']);
        Role::create(['name' => 'workshop_owner']);
        Role::create(['name' => 'admin']);
    }

    private function makeUser(array $overrides = []): User
    {
        $user = User::create(array_merge([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'phone_number' => '0501234567',
            'password' => 'Password123!',
        ], $overrides));

        // ✅ تفعيل البريد عبر method النموذج (email_verified_at ليس fillable — وهذا صحيح أمنياً)
        $user->markEmailAsVerified();

        return $user;
    }

    public function test_login_succeeds_with_valid_credentials(): void
    {
        $this->makeUser();

        $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'Password123!',
        ])->assertOk()->assertJsonStructure(['success', 'message', 'data', 'token']);
    }

    public function test_account_locks_after_five_failed_attempts(): void
    {
        $user = $this->makeUser();

        foreach (range(1, 5) as $i) {
            $this->postJson('/api/auth/login', [
                'email' => $user->email,
                'password' => 'wrong-password',
            ])->assertStatus(401);
        }

        // حتى بكلمة المرور الصحيحة — مقفول (NFR-08)
        $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'Password123!',
        ])->assertStatus(429);
    }

    public function test_suspended_account_cannot_login(): void
    {
        $user = $this->makeUser(['status' => 'suspended']);

        $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'Password123!',
        ])->assertStatus(403);
    }

    public function test_new_registration_gets_customer_role(): void
    {
        Notification::fake();

        $this->postJson('/api/auth/register', [
            'first_name' => 'New',
            'last_name' => 'Customer',
            'email' => 'new@example.com',
            'phone_number' => '+970591234567',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ])->assertStatus(201);

        $user = User::where('email', 'new@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('customer'));
    }
}