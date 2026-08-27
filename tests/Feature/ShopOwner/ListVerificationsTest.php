<?php

namespace Tests\Feature\ShopOwner;

use App\Features\Auth\Models\User;
use App\Features\ShopOwner\Models\Country;
use App\Features\ShopOwner\Models\ShopOwnerVerification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ListVerificationsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'customer']);
    }

    private function makeUser(string $role): User
    {
        $user = User::create([
            'first_name' => ucfirst($role),
            'last_name' => 'Test',
            'email' => $role . '@example.com',
            'phone_number' => $role === 'admin' ? '+970591111111' : '+970592222222',
            'password' => 'Password123!',
        ]);
        $user->assignRole($role);

        return $user;
    }

    private function makeVerification(string $status, string $email, string $phone): ShopOwnerVerification
    {
        $country = Country::firstOrCreate(['name' => 'Palestine']);

        $verification = ShopOwnerVerification::create([
            'first_name' => 'Owner',
            'last_name' => 'Test',
            'email' => $email,
            'phone_number' => $phone,
            'national_id_image' => 'shop-owner/national-ids/test.jpg',
            'country_id' => $country->id,
        ]);

        $verification->status = $status;
        $verification->save();

        return $verification;
    }

    public function test_admin_can_list_pending_verifications_only(): void
    {
        $admin = $this->makeUser('admin');
        $pendingOne = $this->makeVerification('pending', 'one@example.com', '+970593333331');
        $pendingTwo = $this->makeVerification('pending', 'two@example.com', '+970593333332');
        $this->makeVerification('approved', 'three@example.com', '+970593333333');

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/admin/shop-owner-verifications?status=pending')
            ->assertOk()
            ->assertJson(['success' => true])
            ->assertJsonCount(2, 'data');

        $this->assertEqualsCanonicalizing(
            [$pendingOne->id, $pendingTwo->id],
            collect($response->json('data'))->pluck('id')->all()
        );
    }

    public function test_admin_can_list_all_verifications_without_filter(): void
    {
        $admin = $this->makeUser('admin');
        $this->makeVerification('pending', 'one@example.com', '+970593333331');
        $this->makeVerification('approved', 'two@example.com', '+970593333332');
        $this->makeVerification('rejected', 'three@example.com', '+970593333333');

        $this->actingAs($admin, 'sanctum')
            ->getJson('/api/admin/shop-owner-verifications')
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_non_admin_cannot_list_verifications(): void
    {
        $customer = $this->makeUser('customer');

        $this->actingAs($customer, 'sanctum')
            ->getJson('/api/admin/shop-owner-verifications')
            ->assertStatus(403);
    }

    public function test_invalid_status_filter_is_rejected(): void
    {
        $admin = $this->makeUser('admin');

        $this->actingAs($admin, 'sanctum')
            ->getJson('/api/admin/shop-owner-verifications?status=bogus')
            ->assertStatus(422);
    }
}