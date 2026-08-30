<?php

namespace App\Features\ShopOwner\Interfaces;

use App\Features\ShopOwner\DTOs\ProfileShopOwnerDTO;
use App\Features\ShopOwner\DTOs\ShopOwnerVerificationsDTO;

interface ShopOwnerVerificationsInterface
{
    public function create(ShopOwnerVerificationsDTO $shopOwnerVerificationsDTO);

    public function createShopProfile(ProfileShopOwnerDTO $profileShopOwnerDTO);
}
