<?php

namespace App\Features\ShopOwner\UseCases;

use App\Features\Auth\Models\User;
use App\Features\ShopOwner\DTOs\ProfileShopOwnerDTO;
use App\Features\ShopOwner\DTOs\ShopOwnerVerificationsDTO;
use App\Features\ShopOwner\Interfaces\ShopOwnerVerificationsInterface;
use App\Features\ShopOwner\Mail\ShopOwnerAccountApprovedMail;
use App\Features\ShopOwner\Mail\ShopOwnerAccountRejectedMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use RuntimeException;

class ShopOwnerVerifications
{
    public function __construct(
        private ShopOwnerVerificationsInterface $shopOwnerVerificationsInterface
    ) {}

    /**
     * Create a shop owner verification request.
     */
    public function create(
        ShopOwnerVerificationsDTO $shopOwnerVerificationsDTO
    ) {
        return $this->shopOwnerVerificationsInterface->create(
            $shopOwnerVerificationsDTO
        );
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
            //$user->assignRole('shop_owner');

            // Update verification status
            $this->shopOwnerVerificationsInterface
                ->accountCreationApproval($verification->id);

            // Send approval email
            Mail::to($verification->email)->send(
                new ShopOwnerAccountApprovedMail(
                    $verification->first_name,
                    $verification->last_name,
                    $verification->email,
                    $password
                )
            );
        });

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

        Mail::to($verification->email)->send(
            new ShopOwnerAccountRejectedMail(
                $verification->first_name,
                $verification->last_name
            )
        );
    }

    /**
     * Delete a shop owner verification request.
     */
    public function delete(int $id): void
    {
        $this->shopOwnerVerificationsInterface->delete($id);
    }
}