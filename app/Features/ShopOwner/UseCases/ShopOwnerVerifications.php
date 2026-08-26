<?php

namespace  App\Features\ShopOwner\UseCases;

use App\Features\ShopOwner\DTOs\ProfileShopOwnerDTO;
use App\Features\ShopOwner\DTOs\ShopOwnerVerificationsDTO;
use App\Features\ShopOwner\Interfaces\ShopOwnerVerificationsInterface;


class ShopOwnerVerifications
{
    public function __construct(
        public ShopOwnerVerificationsInterface $shopOwnerVerificationsInterface
    ) {}

    public function create(ShopOwnerVerificationsDTO $shopOwnerVerificationsDTO)
    {
        return $this->shopOwnerVerificationsInterface->create($shopOwnerVerificationsDTO);
    }

    public function saveOrUpdateProfile(ProfileShopOwnerDTO $profileShopOwnerDTO)
    {
        return $this->shopOwnerVerificationsInterface->createShopProfile($profileShopOwnerDTO);
    }

    
}