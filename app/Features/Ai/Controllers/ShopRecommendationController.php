<?php

namespace App\Features\Ai\Controllers;

use App\Features\Ai\DTOs\ShopRecommendationDTO;
use App\Features\Ai\Requests\ShopRecommendationRequest;
use App\Features\Ai\UseCases\RecommendShops;
use Illuminate\Http\JsonResponse;

class ShopRecommendationController
{
    /**
     * Recommend the most suitable shops based on
     * the customer's natural language request.
     */
    public function __invoke(
        ShopRecommendationRequest $request,
        RecommendShops $recommendShops
    ): JsonResponse {
        $dto = new ShopRecommendationDTO(
            prompt: $request->validated('prompt'),
        );

        $result = $recommendShops->execute($dto);

        return response()->json([
            'message' => 'تم تحليل طلبك بنجاح.',
            'data' => $result,
        ]);
    }
}