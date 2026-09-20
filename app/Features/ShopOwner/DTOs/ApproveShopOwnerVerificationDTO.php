<?php

namespace App\Features\ShopOwner\DTOs;

use App\Features\ShopOwner\DTOs\ApproveShopOwnerVerificationDTO;

class ApproveShopOwnerVerificationDTO
{
    public function __construct(
        public int $verification_id,
        public string $status,
        public ?string $notes,
    ) {}
}