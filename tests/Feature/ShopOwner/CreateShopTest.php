<?php

namespace Tests\Feature\ShopOwner;

use App\Features\Auth\Models\User;
use App\Features\ShopOwner\Models\City;
use App\Features\ShopOwner\Models\Country;
use App\Features\ShopOwner\Models\Districts;
use App\Features\ShopOwner\Models\Service;
use App\Features\ShopOwner\Models\ShopOwnerVerification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CreateShopTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::create(['name' => 'customer']);
        Role::create(['name' => 'workshop_owner']);
        Role::create(['name' => 'admin']);
    }

    private function makeUser(string $email): User
    {
        $user = User::create([
            'first_name' => 'Owner',
            'last_name' => 'Test',
            'email' => $email,
            'phone_number' => '+970597777777',
            'password' => 'Password123!',
        ]);
        $user->assignRole('customer');

        return $user;
    }

    private function makeVerification(string $email, string $status): void
    {
        $country = Country::firstOrCreate(['name' => 'Palestine']);

        $verification = ShopOwnerVerification::create([
            'first_name' => 'Owner',
            'last_name' => 'Test',
            'email' => $email,
            'phone_number' => '+970598888888',
            'national_id_image' => 'shop-owner/national-ids/test.jpg',
            'country_id' => $country->id,
        ]);

        $verification->status = $status;
        $verification->save();
    }

    private function geo(): array
    {
        $country = Country::firstOrCreate(['name' => 'Palestine']);
        $city = City::create(['name' => 'Gaza', 'country_id' => $country->id]);
        $district = Districts::create(['name' => 'AL-Saraia', 'city_id' => $city->id]);
        $service = Service::create(['name' => 'Mechanics']);

        return [$country, $city, $district, $service];
    }

    private function payload(array $geo, array $overrides = []): array
    {
        return array_merge([
            'shop_name'   => 'Fix Master Garage',
            'description' => 'أفضل ورشة بتصليح المحركات والكهرباء بغزة.',  // 👈 أضف هذا
            'country_id'  => $geo[0]->id,
            'city_id'     => $geo[1]->id,
            'district_id' => $geo[2]->id,
            'street'      => '12 Main St',
            'latitude'      => 31.9028,                          // 👈
            'longitude'     => 35.2034,                          // 👈
            'working_hours' => 'Sat-Thu 8:00-18:00',             // 👈 احتياط
            'service_ids' => [$geo[3]->id],
        ], $overrides);
    }

    public function test_user_with_approved_verification_can_create_shop(): void
    {
        $geo = $this->geo();
        $user = $this->makeUser('owner@example.com');
        $this->makeVerification('owner@example.com', 'approved');

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/shop-owner/shops', $this->payload($geo))
            ->assertCreated()
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('shops', [
            'user_id'   => $user->id,
            'shop_name' => 'Fix Master Garage',
        ]);

        $this->assertTrue($user->fresh()->hasRole('workshop_owner'));
    }

    public function test_user_without_verification_cannot_create_shop(): void
    {
        $geo = $this->geo();
        $user = $this->makeUser('stranger@example.com');

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/shop-owner/shops', $this->payload($geo))
            ->assertStatus(403);
    }

    public function test_user_with_pending_verification_cannot_create_shop(): void
    {
        $geo = $this->geo();
        $user = $this->makeUser('pending@example.com');
        $this->makeVerification('pending@example.com', 'pending');

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/shop-owner/shops', $this->payload($geo))
            ->assertStatus(403);
    }

    public function test_user_cannot_create_second_shop(): void
    {
        $geo = $this->geo();
        $user = $this->makeUser('owner@example.com');
        $this->makeVerification('owner@example.com', 'approved');

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/shop-owner/shops', $this->payload($geo))
            ->assertCreated();

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/shop-owner/shops', $this->payload($geo, ['shop_name' => 'Second Garage']))
            ->assertStatus(422);
    }
}