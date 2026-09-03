<?php

namespace App\Features\ShopOwner\Interfaces;

use App\Features\ShopOwner\DTOs\ProfileShopOwnerDTO;
use App\Features\ShopOwner\DTOs\ShopOwnerVerificationsDTO;
use App\Features\ShopOwner\DTOs\ApproveShopOwnerVerificationDTO;
use App\Features\Auth\Models\User;
use App\Features\ShopOwner\Models\ShopOwnerVerification;            // ← السطر الناقص



interface ShopOwnerVerificationsInterface
{
    public function create(ShopOwnerVerificationsDTO $shopOwnerVerificationsDTO);

    public function createShopProfile(ProfileShopOwnerDTO $profileShopOwnerDTO);

    //public function approve(ApproveShopOwnerVerificationDTO $dto);

    public function findVerificationById(int $id): ?ShopOwnerVerification;

    public function userExistsByEmail(string $email): bool;

    public function createOwnerAccount(ShopOwnerVerification $verification, string $password): User;

    public function markReviewed(ShopOwnerVerification $verification, string $status, ?string $notes): void;

    public function deleteVerification(ShopOwnerVerification $verification): void;

    public function getVerifications(?string $status = null);
}
