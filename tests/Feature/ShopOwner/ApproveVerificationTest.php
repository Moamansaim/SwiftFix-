<?php

namespace Tests\Feature\ShopOwner;

use App\Features\Auth\Models\User;
use App\Features\ShopOwner\Models\Country;
use App\Features\ShopOwner\Models\ShopOwnerVerification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ApproveVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'customer']);
    }

    private function makeAdmin(): User
    {
        $admin = User::create([
            'first_name' => 'Admin',
            'last_name' => 'System',
            'email' => 'admin@example.com',
            'phone_number' => '+970591111111',
            'password' => 'Password123!',
        ]);
        $admin->assignRole('admin');

        return $admin;
    }

    private function makeCustomer(): User
    {
        $customer = User::create([
            'first_name' => 'Customer',
            'last_name' => 'User',
            'email' => 'customer@example.com',
            'phone_number' => '+970593333333',
            'password' => 'Password123!',
        ]);
        $customer->assignRole('customer');

        return $customer;
    }

    private function makeVerification(): ShopOwnerVerification
    {
        $country = Country::firstOrCreate(['name' => 'Palestine']);

        return ShopOwnerVerification::create([
            'first_name' => 'Mohanad',
            'last_name' => 'Owner',
            'email' => 'owner@example.com',
            'phone_number' => '+970592222222',
            'national_id_image' => 'shop-owner/national-ids/test.jpg',
            'country_id' => $country->id,
        ]);
    }

    public function test_admin_can_approve_pending_verification(): void
    {
        $admin = $this->makeAdmin();
        $verification = $this->makeVerification();

        $this->actingAs($admin, 'sanctum')
            ->postJson('/api/admin/shop-owner-verifications/approve', [
                'verification_id' => $verification->id,
                'status' => 'approved',
                'notes' => 'المستندات سليمة',
            ])
            ->assertOk()
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('shop_owner_verifications', [
            'id' => $verification->id,
            'status' => 'approved',
            'reviewed_by' => $admin->id,
        ]);
    }

    public function test_admin_can_reject_pending_verification(): void
    {
        $admin = $this->makeAdmin();
        $verification = $this->makeVerification();

        $this->actingAs($admin, 'sanctum')
            ->postJson('/api/admin/shop-owner-verifications/approve', [
                'verification_id' => $verification->id,
                'status' => 'rejected',
                'notes' => 'الصورة غير واضحة',
            ])
            ->assertOk()
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('shop_owner_verifications', [
            'id' => $verification->id,
            'status' => 'rejected',
        ]);
    }

    public function test_non_admin_cannot_approve_verification(): void
    {
        $customer = $this->makeCustomer();
        $verification = $this->makeVerification();

        $this->actingAs($customer, 'sanctum')
            ->postJson('/api/admin/shop-owner-verifications/approve', [
                'verification_id' => $verification->id,
                'status' => 'approved',
            ])
            ->assertStatus(403);
    }

    public function test_cannot_review_already_reviewed_verification(): void
    {
        $admin = $this->makeAdmin();
        $verification = $this->makeVerification();
        $verification->status = 'approved';
        $verification->save();

        $this->actingAs($admin, 'sanctum')
            ->postJson('/api/admin/shop-owner-verifications/approve', [
                'verification_id' => $verification->id,
                'status' => 'rejected',
            ])
            ->assertStatus(422)
            ->assertJson(['success' => false]);
    }
}