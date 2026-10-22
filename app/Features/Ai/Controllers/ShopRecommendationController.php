<?php

namespace App\Features\Ai\Controllers;

use App\Features\Ai\DTOs\ShopRecommendationDTO;
use App\Features\Ai\Request\ShopRecommendationRequest;
use App\Features\Ai\UseCases\RecommendShops;
use Illuminate\Http\JsonResponse;
use Throwable;

class ShopRecommendationController
{
    /**
     * Handle the AI conversation and workshop search.
     */
    public function __invoke(
        ShopRecommendationRequest $request,
        RecommendShops $recommendShops
    ): JsonResponse {
        try {
            $dto = new ShopRecommendationDTO(
                prompt: $request->validated('prompt'),
                conversationId: $request->validated('conversation_id'),
                latitude: $request->validated('latitude'),
                longitude: $request->validated('longitude'),
            );

            $result = $recommendShops->execute($dto);

            return response()->json([
                'message' => 'تم تحليل طلبك بنجاح.',
                'data' => $result,
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Shop Recommendation Error',
                'error' => $e->getMessage(),
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500);
        }
    }
}