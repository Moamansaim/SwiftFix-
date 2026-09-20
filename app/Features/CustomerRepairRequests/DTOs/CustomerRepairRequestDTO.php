<?php

namespace App\Features\CustomerRepairRequests\DTOs;

use Illuminate\Http\UploadedFile;

class CustomerRepairRequestDTO
{
    public function __construct(
        public int $user_id,
        public int $shop_id,
        public int $device_model_id,
        public int $service_id,
        public string $description,
        public ?UploadedFile $image,
        public string $address,
    ) {}
}