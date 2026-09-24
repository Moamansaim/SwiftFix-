<?php

namespace Tests\Feature\ShopOwner;

use App\Features\Auth\Models\User;
use App\Features\ShopOwner\DTOs\ProfileShopOwnerDTO;
use App\Features\ShopOwner\Models\ShopOwnerVerification;
use App\Features\ShopOwner\UseCases\ShopOwnerVerifications;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SaveShopProfileGuardTest extends TestCase
{
    use RefreshDatabase;

    private int $countryId;

    protected function setUp(): void
    {
        parent::setUp();
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'customer']);
        Role::create(['name' => 'workshop_owner']);
        $this->countryId = DB::table('countries')->insertGetId(['name' => 'Palestine']);
    }

    private function makeOwnerWithVerification(string $status): User
    {
        $owner = User::create([
            'first_name' => 'Owner',
            'last_name' => 'Test',
            'email' => 'owner@example.com',
            'phone_number' => '+970596666666',
            'password' => 'Password123!',
        ]);
        $owner->assignRole('workshop_owner');

        $verification = ShopOwnerVerification::create([
            'first_name' => 'Owner',
            'last_name' => 'Test',
            'email' => $owner->email,
            'phone_number' => '+970596666666',
            'national_id_image' => 'shop-owner/national-ids/test.jpg',
            'country_id' => $this->countryId,
        ]);
        $verification->status = $status;
        $verification->save();

        if ($status === 'rejected') {
            $verification->delete(); // مثل الفلو الحقيقي: حذف ناعم
        }

        return $owner;
    }

    private function makeDto(): ProfileShopOwnerDTO
    {
        // ⚠️ رتّب الوسائط حسب ملف ProfileShopOwnerDTO عندك
        // (إذا ما عندكم commercial_record_image بالـ DTO احذف السطر الرابع)
        return new ProfileShopOwnerDTO(
            'ورشة الاختبار',
            'وصف الورشة',
            UploadedFile::fake()->image('cover.jpg'),
            null, // commercial_record_image
            $this->countryId,
            DB::table('cities')->insertGetId(['name' => 'Gaza', 'country_id' => $this->countryId]),
            'الحي',
            'شارع 1',
            31.9,
            35.2,
            ['sat' => ['09:00', '18:00']],
            [[
                'service_id' => DB::table('services')->insertGetId(['service_name' => 'تبديل شاشة']),
                'price' => 150,
            ]],
        );
    }

    public function test_owner_with_pending_verification_cannot_save_shop_profile(): void
    {
        $owner = $this->makeOwnerWithVerification('pending');
        $this->actingAs($owner, 'sanctum');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('اعتماد طلب توثيقك');

        app(ShopOwnerVerifications::class)->saveOrUpdateProfile($this->makeDto());
    }

    public function test_owner_with_rejected_verification_cannot_save_shop_profile(): void
    {
        $owner = $this->makeOwnerWithVerification('rejected');
        $this->actingAs($owner, 'sanctum');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('اعتماد طلب توثيقك');   // ← أضف هذا السطر

        app(ShopOwnerVerifications::class)->saveOrUpdateProfile($this->makeDto());
    }

    public function test_owner_with_approved_verification_can_save_shop_profile(): void
    {
        Storage::fake('public');
        $owner = $this->makeOwnerWithVerification('approved');
        $this->actingAs($owner, 'sanctum');

        app(ShopOwnerVerifications::class)->saveOrUpdateProfile($this->makeDto());

        $this->assertDatabaseHas('shops', [
            'user_id' => $owner->id,
            'shop_name' => 'ورشة الاختبار',
        ]);
    }
}