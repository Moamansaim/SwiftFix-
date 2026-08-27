<?php

namespace App\Features\ShopOwner\UseCases;

use App\Features\Auth\Models\User;
use App\Features\ShopOwner\DTOs\CreateShopDTO;
use App\Features\ShopOwner\Interfaces\ShopOwnerVerificationsInterface;

class CreateShop
{
    public function __construct(
        public ShopOwnerVerificationsInterface $shopOwnerVerificationsInterface
    ) {}

    public function handle(CreateShopDTO $dto)
    {
        $user = User::findOrFail($dto->user_id);

        $verification = $this->shopOwnerVerificationsInterface
            ->findApprovedVerificationByEmail($user->email);

        if (! $verification) {
            return [
                'error'   => true,
                'status'  => 403,
                'message' => 'لا يوجد توثيق صاحب ورشة موافق عليه لهذا البريد.',
            ];
        }

        if ($this->shopOwnerVerificationsInterface->userAlreadyHasShop($dto->user_id)) {
            return [
                'error'   => true,
                'status'  => 422,
                'message' => 'لديك ورشة مسجلة مسبقاً.',
            ];
        }

        if (! $user->hasRole('workshop_owner')) {
            $user->assignRole('workshop_owner');
        }

        return $this->shopOwnerVerificationsInterface->createShop($dto);
    }
}