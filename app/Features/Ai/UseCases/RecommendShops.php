<?php

namespace App\Features\Ai\UseCases;

use App\Features\Ai\DTOs\ShopRecommendationDTO;
use App\Features\Ai\Models\AiConversation;
use App\Features\Ai\Models\AiMessage;
use App\Features\Ai\Services\GeminiService;
use App\Features\ShopOwner\Models\Shop;
use Illuminate\Support\Facades\Auth;

class RecommendShops
{
    public function __construct(
        private GeminiService $geminiService
    ) {
    }

    /**
     * Analyze the customer request and recommend suitable workshops.
     *
     * Laravel provides the real workshop data.
     * Gemini understands the customer request and analyzes
     * the available workshops using the full conversation context.
     *
     * @return array<string, mixed>
     */
    public function execute(
        ShopRecommendationDTO $dto
    ): array {
        /*
         * Get the existing conversation or create a new one.
         */
        $conversation = $this->getConversation(
            $dto->conversationId ?? null
        );

        /*
         * IMPORTANT:
         *
         * Get the previous conversation BEFORE saving
         * the current user message.
         *
         * This prevents the current prompt from being
         * sent to Gemini twice.
         */
        $conversationHistory = $conversation
            ->messages()
            ->orderBy('id')
            ->get()
            ->map(function (AiMessage $message): array {
                return [
                    'role' => $message->role,
                    'message' => $message->message,
                ];
            })
            ->all();

        /*
         * Get ALL valid public workshops.
         *
         * There is intentionally NO filtering here by:
         *
         * - service
         * - device
         * - brand
         * - model
         * - spare part
         * - price
         * - rating
         * - city
         *
         * Gemini analyzes the workshop data.
         */
        $shops = $this->getAllShops();

        /*
         * Convert Eloquent models into clean data for Gemini.
         */
        $shopData = $this->prepareShopData($shops);

        /*
         * Gemini receives:
         *
         * 1. Full previous conversation.
         * 2. Current customer request.
         * 3. Real workshop data from the database.
         */
        $result = $this->geminiService->recommendShops(
            userPrompt: $dto->prompt,
            shops: $shopData,
            conversation: $conversationHistory
        );

        /*
         * Save the current user message AFTER sending it
         * to Gemini.
         */
        $conversation->messages()->create([
            'role' => 'user',
            'message' => $dto->prompt,
        ]);

        /*
         * Save Gemini's response.
         */
        $conversation->messages()->create([
            'role' => 'assistant',
            'message' => $result['message'],
        ]);

        /*
         * Convert Gemini recommendations into the format
         * required by React.
         *
         * Laravel uses the real Shop models, not Gemini data.
         */
        $recommendations = $this->buildRecommendations(
            recommendations: $result['recommendations'] ?? [],
            shops: $shops
        );

        return [
            'conversation_id' => $conversation->id,
            'message' => $result['message'],
            'intent' => $result['intent'],
            'recommendations' => $recommendations,
        ];
    }

    /**
     * Get the conversation belonging to the authenticated user.
     */
    private function getConversation(
        ?int $conversationId
    ): AiConversation {
        if ($conversationId !== null) {
            return AiConversation::query()
                ->where('id', $conversationId)
                ->where('user_id', Auth::id())
                ->firstOrFail();
        }

        return AiConversation::create([
            'user_id' => Auth::id(),
        ]);
    }

    /**
     * Get all public workshops.
     *
     * Only workshops that are:
     *
     * - verified
     * - not blocked
     * - have a shop name
     *
     * are provided to Gemini.
     */
    private function getAllShops()
    {
        return Shop::query()
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
    }

    /**
     * Prepare real workshop data for Gemini.
     *
     * Gemini receives only data that exists in the database.
     *
     * @param \Illuminate\Support\Collection<int, Shop> $shops
     * @return array<int, array<string, mixed>>
     */
    private function prepareShopData($shops): array
    {
        return $shops
            ->map(function (Shop $shop): array {
                return [
                    'id' => $shop->id,

                    'shop_name' => $shop->shop_name,

                    'description' => $shop->description,

                    'country' => $shop->country?->name,

                    'city' => $shop->city?->name,

                    'district' => $shop->district,

                    'street' => $shop->street,

                    'latitude' => $shop->latitude,

                    'longitude' => $shop->longitude,

                    'working_hours' => $shop->working_hours,

                    'rating' => $this->calculateRating($shop),

                    'services' => $shop->services
                        ->map(function ($service): array {
                            return [
                                'id' => $service->id,

                                'service_name' =>
                                    $service->service_name,

                                'price' =>
                                    $service->pivot?->price,
                            ];
                        })
                        ->values()
                        ->all(),

                    'spare_parts' => $shop->shopProducts
                        ->map(function ($shopProduct): array {
                            return [
                                'product_id' =>
                                    $shopProduct->product_id,

                                'product_name' =>
                                    $shopProduct->product?->product_name,

                                'quantity' =>
                                    $shopProduct->quantity,

                                'price' =>
                                    $shopProduct->price,
                            ];
                        })
                        ->values()
                        ->all(),
                ];
            })
            ->values()
            ->all();
    }

    /**
     * Calculate the real workshop rating.
     *
     * This is calculated from the loaded reviews instead of
     * trusting a generated value.
     */
    private function calculateRating(Shop $shop): ?float
    {
        if ($shop->reviews->isEmpty()) {
            return null;
        }

        return round(
            (float) $shop->reviews->avg('rating'),
            2
        );
    }

    /**
     * Build the final recommendations using real Shop models.
     *
     * Gemini only decides which shop IDs are relevant.
     * Laravel retrieves the actual workshop data.
     *
     * @param array<int, array<string, mixed>> $recommendations
     * @param \Illuminate\Support\Collection<int, Shop> $shops
     * @return array<int, array<string, mixed>>
     */
    private function buildRecommendations(
        array $recommendations,
        $shops
    ): array {
        /*
         * Create:
         *
         * [
         *     shop_id => Shop
         * ]
         */
        $shopsById = $shops->keyBy('id');

        return collect($recommendations)
            ->map(function (array $recommendation) use ($shopsById) {
                $shopId = (int) ($recommendation['shop_id'] ?? 0);

                /*
                 * Never trust Gemini blindly.
                 *
                 * If the ID does not exist in the actual database
                 * result, ignore it.
                 */
                $shop = $shopsById->get($shopId);

                if (! $shop) {
                    return null;
                }

                return [
                    'id' => $shop->id,

                    'shop_name' => $shop->shop_name,

                    /*
                     * Rank comes from the order returned by Gemini.
                     *
                     * The actual workshop data still comes from Laravel.
                     */
                    'rank' => (int) (
                        $recommendation['rank'] ?? 0
                    ),

                    /*
                     * This is Gemini's explanation.
                     *
                     * It should NOT be treated as authoritative
                     * database information.
                     */
                    'reason' => (string) (
                        $recommendation['reason'] ?? ''
                    ),

                    'confidence' => (
                        $recommendation['confidence']
                        ?? 'medium'
                    ),
                ];
            })
            ->filter()
            ->values()
            ->all();
    }
}