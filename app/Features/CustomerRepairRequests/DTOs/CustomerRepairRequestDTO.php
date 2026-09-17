<?php

namespace App\Features\CustomerRepairRequest\DTOs;

class CustomerRepairRequestDTO
{
    public function __construct(
        public int $user_id,
        public int $shop_id,
        public int $device_model_id,
        public int $service_id,
        public string $description,
        public ?string $image,
        public string $phone_number,
        public string $address,
    ) {}
}