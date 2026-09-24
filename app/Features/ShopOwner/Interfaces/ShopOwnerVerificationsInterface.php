<?php

namespace App\Features\ShopOwner\Interfaces;

use App\Features\ShopOwner\DTOs\ProfileShopOwnerDTO;
use App\Features\ShopOwner\DTOs\ShopOwnerVerificationsDTO;
use App\Features\ShopOwner\DTOs\ApproveShopOwnerVerificationDTO;
use App\Features\Auth\Models\User;
use App\Features\ShopOwner\Models\ShopOwnerVerification;            // ← السطر الناقص


interface ShopOwnerVerificationsInterface
{
    /**
     * Create a shop owner verification request.
     */
    public function create(
        ShopOwnerVerificationsDTO $shopOwnerVerificationsDTO
    ): ShopOwnerVerification;

    /**
     * Create a shop owner profile.
     *
     * @return mixed
     */
    public function createShopProfile(ProfileShopOwnerDTO $profileShopOwnerDTO);


    public function userExistsByEmail(string $email): bool;

    public function createOwnerAccount(ShopOwnerVerification $verification, string $password): User;

    public function getVerifications(?string $status = null);



    /**
     * Find a shop owner verification by ID.
     */
    public function findById(int $id): ShopOwnerVerification;

    /**
     * Update the verification status.
     */
    public function updateStatus(
        ShopOwnerVerification $verification,
        string $status
    ): void;

    /**
     * Approve a shop owner verification request
     * and create the shop owner account.
     */
    public function accountCreationApproval(int $id): void;

    /**
     * Reject a shop owner verification request.
     */
    public function accountCreationRefused(int $id, ?string $notes = null): void;
    /**
     * Delete a shop owner verification request.
     */
    public function delete(int $id): void;

    public function verifyShop(int $shopId): void;
    
    public function hasApprovedVerification(string $email): bool;
}
