<?php

namespace App\Features\Ai\DTOs;

class ShopRecommendationDTO
{
    public function __construct(
        public string $prompt,
        public ?float $latitude = null,
        public ?float $longitude = null,
    ) {}
}