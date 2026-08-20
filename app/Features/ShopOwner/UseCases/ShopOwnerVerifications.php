<?php

namespace  App\Features\ShopOwner\UseCases;

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
}