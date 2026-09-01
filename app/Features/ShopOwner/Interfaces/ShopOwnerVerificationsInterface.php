<?php

namespace App\Features\ShopOwner\Interfaces;

use App\Features\ShopOwner\DTOs\ProfileShopOwnerDTO;
use App\Features\ShopOwner\DTOs\ShopOwnerVerificationsDTO;
use App\Features\ShopOwner\DTOs\ApproveShopOwnerVerificationDTO;


interface ShopOwnerVerificationsInterface
{
    public function create(ShopOwnerVerificationsDTO $shopOwnerVerificationsDTO);

    public function createShopProfile(ProfileShopOwnerDTO $profileShopOwnerDTO);

    public function approve(ApproveShopOwnerVerificationDTO $dto);

    public function getVerifications(?string $status = null);
}
