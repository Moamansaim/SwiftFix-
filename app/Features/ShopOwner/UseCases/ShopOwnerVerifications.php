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

    public function getAllCountries()
    {
        return $this->shopOwnerVerificationsInterface->getAllCountries();
    }

    public function getAllServices()
    {
        return $this->shopOwnerVerificationsInterface->getAllServices();
    }

    public function getAllCity($id)
    {
        return $this->shopOwnerVerificationsInterface->getAllCity($id);
    }

    public function getAllDistrict($id)
    {
        return $this->shopOwnerVerificationsInterface->getAllDistrict($id);
    }

    public function saveOrUpdateProfile(ProfileShopOwnerDTO $profileShopOwnerDTO)
    {
        return $this->shopOwnerVerificationsInterface->createShopProfile($profileShopOwnerDTO);
    }
}