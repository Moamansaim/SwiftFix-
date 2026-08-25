<?php

namespace App\Features\ShopOwner\DTOs;

class ApproveShopOwnerVerificationDTO
{
    public function __construct(
        public int $verification_id,
        public string $status,          // approved | rejected
        public ?string $notes,
        public int $reviewed_by,
    ) {}
}