<?php

namespace App\Features\Review\Controllers;

use App\Features\Review\Models\Review;
use App\Features\Review\Resources\ReviewResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ShopReviewController extends Controller
{
    /**
     * Get all visible reviews for a shop.
     */
    public function index(int $shopId): JsonResponse
    {
        $reviewsQuery = Review::where('shop_id', $shopId);

        $averageRating = $reviewsQuery->avg('rating');

        $reviewsCount = $reviewsQuery->count();

        $ratingDistribution = Review::where('shop_id', $shopId)
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating');

        $reviews = Review::with('user')
            ->where('shop_id', $shopId)
            ->latest()
            ->get();

        return response()->json([
            'average_rating' => round($averageRating ?? 0, 1),

            'reviews_count' => $reviewsCount,

            'rating_distribution' => [
                '5' => $ratingDistribution->get(5, 0),
                '4' => $ratingDistribution->get(4, 0),
                '3' => $ratingDistribution->get(3, 0),
                '2' => $ratingDistribution->get(2, 0),
                '1' => $ratingDistribution->get(1, 0),
            ],

            'reviews' => ReviewResource::collection($reviews),
        ], 200);
    }
}
