<?php

namespace App\Features\ShopOwner\DTOs;

use Illuminate\Http\UploadedFile;

class ShopOwnerVerificationsDTO
{
    /**
     * @param  array<int, int>  $service_ids
     */
    public function __construct(
        public string $first_name,
        public string $last_name,
        public string $email,
        public string $phone_number,
        public  UploadedFile $national_id_image,
        public ?UploadedFile $commercial_record_image,
        public int $country_id,
        public array $service_ids,
        public ?string $notes,
    ) {}
}