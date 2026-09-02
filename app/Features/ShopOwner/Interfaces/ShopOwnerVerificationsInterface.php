<?php

namespace App\Features\ShopOwner\Interfaces;

use App\Features\ShopOwner\DTOs\ProfileShopOwnerDTO;
use App\Features\ShopOwner\DTOs\ShopOwnerVerificationsDTO;
use App\Features\ShopOwner\Models\ShopOwnerVerification;

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
    public function createShopProfile(
        ProfileShopOwnerDTO $profileShopOwnerDTO
    );

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
    public function accountCreationRefused(int $id): void;

    /**
     * Delete a shop owner verification request.
     */
    public function delete(int $id): void;
}
