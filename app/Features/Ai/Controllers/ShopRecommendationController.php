<?php

namespace App\Features\Ai\Controllers;

use App\Features\Ai\DTOs\ShopRecommendationDTO;
use App\Features\Ai\Request\ShopRecommendationRequest;
use App\Features\Ai\UseCases\RecommendShops;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
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
            // Record the complete error in Laravel logs.
            Log::error('Shop recommendation failed.', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Return detailed errors only when APP_DEBUG=true.
            return response()->json([
                'message' => $e->getMessage(),
                'error' => config('app.debug')
                    ? $e->getMessage()
                    : 'Internal Server Error',
                'exception' => config('app.debug')
                    ? get_class($e)
                    : null,
                'file' => config('app.debug')
                    ? $e->getFile()
                    : null,
                'line' => config('app.debug')
                    ? $e->getLine()
                    : null,
            ], 500);
        }
    }
}