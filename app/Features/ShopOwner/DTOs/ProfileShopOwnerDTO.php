<?php

namespace App\Features\ShopOwner\DTOs;

use Illuminate\Http\UploadedFile;

class ProfileShopOwnerDTO
{
    /**
     * @param  array<string, array<string, string>>  $working_hours
     * @param  array<int, int>  $service_ids
     */
    public function __construct(
        public string $shop_name,
        public string $description,
        public UploadedFile $cover_image,
        public int $country_id,
        public int $city_id,
        public int $district_id,
        public string $street,
        public float $latitude,
        public float $longitude,
        public array $working_hours,
        public array $service_ids
    ) {}
}
