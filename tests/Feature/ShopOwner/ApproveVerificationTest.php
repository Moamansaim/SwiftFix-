<?php

namespace Tests\Feature\ShopOwner;

use App\Features\Auth\Models\User;
use App\Features\ShopOwner\Mail\ShopOwnerApprovedMail;
use App\Features\ShopOwner\Mail\ShopOwnerRejectedMail;
use App\Features\ShopOwner\Models\ShopOwnerVerification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ApproveVerificationTest extends TestCase
{
    use RefreshDatabase;

    private int $countryId;
    private int $counter = 0;

    protected function setUp(): void
    {
        parent::setUp();
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'customer']);
        Role::create(['name' => 'workshop_owner']);
        $this->countryId = DB::table('countries')->insertGetId(['name' => 'Palestine']);
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

    private function makeVerification(string $status = 'pending'): ShopOwnerVerification
    {
        $this->counter++;

        $verification = ShopOwnerVerification::create([
            'first_name' => 'Owner',
            'last_name' => 'Test',
            'email' => "owner{$this->counter}@example.com",
            'phone_number' => '+97059333' . str_pad((string) $this->counter, 4, '0', STR_PAD_LEFT),
            'national_id_image' => 'shop-owner/national-ids/test.jpg',
            'country_id' => $this->countryId,
        ]);

        $verification->status = $status;
        $verification->save();

        return $verification;
    }

    public function test_admin_can_approve_pending_verification(): void
    {
        Mail::fake();
        $admin = $this->makeUser('admin');
        $verification = $this->makeVerification();

        $this->actingAs($admin, 'sanctum')
            ->patchJson("/api/admin/shop-owner/verification/{$verification->id}/approve")
            ->assertOk()
            ->assertJson(['success' => true]);

        $owner = User::where('email', $verification->email)->first();
        $this->assertNotNull($owner);
        $this->assertTrue($owner->hasRole('workshop_owner'));

        $this->assertDatabaseHas('shop_owner_verifications', [
            'id' => $verification->id,
            'status' => 'approved',
            'reviewed_by' => $admin->id,
        ]);

        Mail::assertSent(ShopOwnerApprovedMail::class, fn ($mail) => $mail->hasTo($verification->email));
    }

    public function test_admin_can_reject_pending_verification(): void
    {
        Mail::fake();
        $admin = $this->makeUser('admin');
        $verification = $this->makeVerification();

        $this->actingAs($admin, 'sanctum')
            ->patchJson("/api/admin/shop-owner/verification/{$verification->id}/reject", [
                'notes' => 'الصورة غير واضحة',
            ])
            ->assertOk()
            ->assertJson(['success' => true]);

        $this->assertSoftDeleted('shop_owner_verifications', ['id' => $verification->id]);

        Mail::assertSent(ShopOwnerRejectedMail::class, fn ($mail) => $mail->hasTo($verification->email));
    }

    public function test_approval_fails_if_user_already_exists(): void
    {
        Mail::fake();
        $admin = $this->makeUser('admin');
        $verification = $this->makeVerification();

        User::create([
            'first_name' => 'Existing',
            'last_name' => 'User',
            'email' => $verification->email,
            'phone_number' => '+970597777777',
            'password' => 'Password123!',
        ]);

        $this->actingAs($admin, 'sanctum')
            ->patchJson("/api/admin/shop-owner/verification/{$verification->id}/approve")
            ->assertStatus(422);
    }

    public function test_non_admin_cannot_approve_verification(): void
    {
        $customer = $this->makeUser('customer');
        $verification = $this->makeVerification();

        $this->actingAs($customer, 'sanctum')
            ->patchJson("/api/admin/shop-owner/verification/{$verification->id}/approve")
            ->assertStatus(403);
    }

    public function test_cannot_review_already_reviewed_verification(): void
    {
        $admin = $this->makeUser('admin');
        $verification = $this->makeVerification('approved');

        $this->actingAs($admin, 'sanctum')
            ->patchJson("/api/admin/shop-owner/verification/{$verification->id}/reject")
            ->assertStatus(422);
    }
}