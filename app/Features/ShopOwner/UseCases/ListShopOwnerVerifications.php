<?php

namespace App\Features\ShopOwner\UseCases;

use App\Features\ShopOwner\Interfaces\ShopOwnerVerificationsInterface;

class ListShopOwnerVerifications
{
    public function __construct(
        public ShopOwnerVerificationsInterface $shopOwnerVerificationsInterface
    ) {}

    public function handle(?string $status = null)
    {
        return $this->shopOwnerVerificationsInterface->getVerifications($status);
    }
}