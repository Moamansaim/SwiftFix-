<?php

namespace App\Features\Home;

use App\Features\Auth\Models\User;
use App\Features\CustomerRepairRequests\Models\CustomerRepairRequest;
use App\Features\Review\Models\Review;
use App\Features\ShopOwner\Models\Shop;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class PlatformController extends Controller
{
    /**
     * Get the main landing page statistics.
     *
     * @return JsonResponse
     *
     * @hint Returns the total number of registered customers,
     *        completed repair requests, excellent reviews with
     *        ratings of 4 or 5, verified shops, and customer
     *        reviews with their names and ratings.
     */
    public function getStatistics(): JsonResponse
    {
        $customersCount = User::role('customer')->count();

        $completedRequestsCount = CustomerRepairRequest::where(
            'status',
            'completed'
        )->count();

        $excellentReviewsCount = Review::whereIn(
            'rating',
            [4, 5]
        )->count();

        $verifiedShopsCount = Shop::where(
            'is_verified',
            true
        )->count();

        $reviews = Review::with('user')
            ->whereNotNull('comment')
            ->where('comment', '!=', '')
            ->get()
            ->map(function ($review) {
                return [
                    'customer_name' => trim(
                        $review->user?->first_name . ' ' .
                            $review->user?->last_name
                    ),
                    'comment' => $review->comment,
                    'rating' => $review->rating,
                ];
            });

        return response()->json([
            'customers_count' => $customersCount,
            'completed_requests_count' => $completedRequestsCount,
            'excellent_reviews_count' => $excellentReviewsCount,
            'verified_shops_count' => $verifiedShopsCount,
            'reviews' => $reviews,
        ], 200);
    }
}