<?php

namespace App\Features\ShopOwner\Interfaces;

use App\Features\ShopOwner\DTOs\ProfileShopOwnerDTO;
use App\Features\ShopOwner\DTOs\ShopOwnerVerificationsDTO;
use App\Features\ShopOwner\Models\Shop;
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
     */
    public function createShopProfile(
        ProfileShopOwnerDTO $profileShopOwnerDTO
    ): Shop;

    /**
     * Approve a shop owner verification request.
     */
    public function accountCreationApproval(
        ShopOwnerVerification $verification
    ): void;

    /**
     * Reject a shop owner verification request.
     */
    public function accountCreationRefused(
        ShopOwnerVerification $verification
    ): void;

    /**
     * Delete a shop owner verification request.
     */
    public function delete(
        ShopOwnerVerification $verification
    ): void;
}