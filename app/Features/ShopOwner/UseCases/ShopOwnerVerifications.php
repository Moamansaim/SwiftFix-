<?php

namespace App\Features\ShopOwner\UseCases;



use App\Features\ShopOwner\Mail\ShopOwnerApprovedMail;
use App\Features\ShopOwner\Mail\ShopOwnerRejectedMail;
use App\Features\Auth\Models\User;
use App\Features\Auth\Notifications\NewShopOwnerVerification;
use App\Features\ShopOwner\DTOs\ProfileShopOwnerDTO;
use App\Features\ShopOwner\DTOs\ShopOwnerVerificationsDTO;
use App\Features\ShopOwner\Events\NewShopOwnerVerificationEvent;
use App\Features\ShopOwner\Interfaces\ShopOwnerVerificationsInterface;
use App\Features\ShopOwner\Models\Shop;
use App\Features\ShopOwner\Models\ShopOwnerVerification;
use App\Features\ShopOwner\Services\ShopOwnerMail;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use RuntimeException;

class ShopOwnerVerifications
{
    public function __construct(
        private ShopOwnerVerificationsInterface $shopOwnerVerificationsInterface,
        private ShopOwnerMail $shopOwnerMail
    ) {}


    /**
     * Create a shop owner verification request.
     */
    public function create(
        ShopOwnerVerificationsDTO $shopOwnerVerificationsDTO
    ): ShopOwnerVerification {
        $verification = $this->shopOwnerVerificationsInterface->create(
            $shopOwnerVerificationsDTO
        );

        $admins = User::role('admin')->get();

        $admins->each(function ($admin) use ($verification) {
            $admin->notify(
                new NewShopOwnerVerification($verification)
            );
        });

        NewShopOwnerVerificationEvent::dispatch($verification);

        return $verification;
    }

    /**
     * Create or update the shop profile.
     */
    public function saveOrUpdateProfile(
        ProfileShopOwnerDTO $profileShopOwnerDTO
    ) {
        return $this->shopOwnerVerificationsInterface->createShopProfile(
            $profileShopOwnerDTO
        );
    }

    public function accountCreationApproval(int $id): string
    {
        $verification = $this->shopOwnerVerificationsInterface->findById($id);

        if ($verification->status !== 'pending') {
            throw new RuntimeException(
                'تمت معالجة طلب التحقق هذا مسبقًا.'
            );
        }

        $password = Str::random(12);

        try {
            DB::transaction(function () use (
                $verification,
                $password
            ) {
                $user = User::create([
                    'first_name' => $verification->first_name,
                    'last_name' => $verification->last_name,
                    'email' => $verification->email,
                    'phone_number' => $verification->phone_number,
                    'password' => Hash::make($password),
                ]);

                // Assign shop owner role
                $user->assignRole('shopOwner');

                // Create empty shop
                Shop::create([
                    'user_id' => $user->id,
                    'is_verified' => false,
                ]);

                // Update verification status
                $this->shopOwnerVerificationsInterface
                    ->accountCreationApproval($verification->id);

                // Send approval email
                $this->shopOwnerMail->sendAccountApproval(
                    $verification,
                    $password
                );
            });
        } catch (QueryException $e) {

            // MySQL duplicate entry
            if ($e->errorInfo[0] === '23000' && $e->errorInfo[1] === 1062) {
                if (str_contains($e->getMessage(), 'phone_number')) {
                    throw new RuntimeException(
                        'رقم الهاتف مستخدم بالفعل، لا يمكن استخدامه لأكثر من حساب.'
                    );
                }

                if (str_contains($e->getMessage(), 'email')) {
                    throw new RuntimeException(
                        'البريد الإلكتروني مستخدم بالفعل، لا يمكن استخدامه لأكثر من حساب.'
                    );
                }
            }

            throw $e;
        }

        return $password;
    }

    public function accountCreationRefused(int $id): void
    {
        $verification = $this->shopOwnerVerificationsInterface->findById($id);

        if ($verification->status !== 'pending') {
            throw new RuntimeException(
                'تمت معالجة طلب التحقق هذا مسبقًا.'
            );
        }

        $this->shopOwnerVerificationsInterface
            ->accountCreationRefused($id);

        // Send rejection email
        $this->shopOwnerMail->sendAccountRejection(
            $verification
        );
    }

    /**
     * Delete a shop owner verification request.
     */
    public function delete(int $id): void
    {
        $this->shopOwnerVerificationsInterface->delete($id);
    }


    // Verify a shop and update its status to verified 
    public function verifyShop(int $Id): void
    {
        $shop = Shop::findOrFail($Id);

        if ($shop->is_verified) {
            throw new RuntimeException(
                'الورشة موثقة مسبقًا.'
            );
        }

        $shop->update([
            'is_verified' => true,
        ]);
    }
}