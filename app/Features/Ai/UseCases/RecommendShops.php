<?php

namespace App\Features\Ai\UseCases;

use App\Features\Ai\DTOs\ShopRecommendationDTO;
use App\Features\Ai\Services\GeminiService;
use App\Features\ShopOwner\Models\Shop;

class RecommendShops
{
    public function __construct(
        private GeminiService $geminiService,
    ) {
    }

    /**
     * Analyze the customer's request and recommend
     * the most suitable workshops.
     */
    public function execute(
        ShopRecommendationDTO $dto
    ): array {
        /*
        |--------------------------------------------------------------------------
        | Get available shops
        |--------------------------------------------------------------------------
        |
        | We load only the relations that are useful for AI recommendations.
        |
        */

        $shops = Shop::query()
            ->where('is_verified', true)
            ->where('status', '!=', 'blocked')
            ->whereNotNull('shop_name')
            ->with([
                'services',
                'shopProducts.product',
                'reviews',
                'country',
                'city',
            ])
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Prepare shop data for Gemini
        |--------------------------------------------------------------------------
        |
        | We should NOT send the complete Eloquent models to Gemini.
        | Instead, we prepare a clean array containing only useful data.
        |
        */

        $shopData = $shops->map(
            function (Shop $shop): array {
                return [
                    'shop_id' => $shop->id,

                    'shop_name' => $shop->shop_name,

                    'description' => $shop->description,

                    'status' => $shop->status,

                    'is_verified' => (bool) $shop->is_verified,

                    'country' => $shop->country?->name,

                    'city' => $shop->city?->name,

                    'services' => $shop->services
                        ->map(function ($service): array {
                            return [
                                'service_id' => $service->id,

                                'service_name' => $service->name,

                                'price' => $service->pivot?->price,
                            ];
                        })
                        ->values()
                        ->all(),

                    'products' => $shop->shopProducts
                        ->map(function ($shopProduct): array {
                            return [
                                'product_id' => $shopProduct->product?->id,

                                'product_name' =>
                                    $shopProduct->product?->product_name,

                                'price' => $shopProduct->price,

                                'quantity' => $shopProduct->quantity,

                                'status' => $shopProduct->status,
                            ];
                        })
                        ->values()
                        ->all(),

                    'reviews' => $shop->reviews
                        ->map(function ($review): array {
                            return [
                                'rating' => $review->rating,

                                'comment' => $review->comment,
                            ];
                        })
                        ->values()
                        ->all(),
                ];
            }
        )
        ->values()
        ->all();

        /*
        |--------------------------------------------------------------------------
        | Send customer request + shop data to Gemini
        |--------------------------------------------------------------------------
        */

        return $this->geminiService->recommendShops(
            $dto->prompt,
            $shopData
        );
    }
}