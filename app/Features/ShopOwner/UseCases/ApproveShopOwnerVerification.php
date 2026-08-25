<?php

namespace App\Features\ShopOwner\UseCases;

use App\Features\ShopOwner\DTOs\ApproveShopOwnerVerificationDTO;
use App\Features\ShopOwner\Interfaces\ShopOwnerVerificationsInterface;

class ApproveShopOwnerVerification
{
    public function __construct(
        public ShopOwnerVerificationsInterface $shopOwnerVerificationsInterface
    ) {}

    public function handle(ApproveShopOwnerVerificationDTO $dto)
    {
        return $this->shopOwnerVerificationsInterface->approve($dto);
    }
}
