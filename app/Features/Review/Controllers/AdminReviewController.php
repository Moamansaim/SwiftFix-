<?php

namespace App\Features\Review\Controllers;

use App\Features\Review\Models\Review;
use App\Features\Review\Resources\ReviewResource;
use App\Features\ShopOwner\Models\Shop;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class AdminReviewController extends Controller
{
    /**
     * Get all reviews grouped by shop.
     *
     * @return JsonResponse
     *
     * @hint Retrieves all shops that have reviews and groups their reviews
     *       under each shop. Each shop includes its review count, average
     *       rating, and the related customer data for every review.
     */
    public function index(): JsonResponse
    {
        Gate::authorize('viewAllShopReviews', Review::class);

        $shops = Shop::with([
            'reviews' => function ($query) {
                $query->with('user')
                    ->latest();
            },
        ])
            ->whereHas('reviews')
            ->get();

        return response()->json([
            'shops' => $shops->map(function ($shop) {
                return [
                    'shop_id' => $shop->id,
                    'shop_name' => $shop->shop_name,
                    'reviews_count' => $shop->reviews->count(),
                    'average_rating' => round(
                        $shop->reviews->avg('rating') ?? 0,
                        1
                    ),
                    'reviews' => ReviewResource::collection($shop->reviews),
                ];
            }),
        ], 200);
    }


    /**
     * Remove the customer's comment from a review.
     *
     * This method allows an administrator to remove the textual
     * comment submitted by a customer while keeping the review
     * record and its rating intact.
     *
     * The review itself is not deleted from the database.
     * Only the `comment` field is set to null.
     *
     * This preserves:
     * - The review ID.
     * - The user who submitted the review.
     * - The shop associated with the review.
     * - The rating value.
     * - The shop's reply, if available.
     * - The review timestamps.
     *
     * If the review does not exist, a 404 response is returned.
     *
     * @param int $id
     *        The unique identifier of the review.
     *
     * @return JsonResponse
     *         Returns a success response after removing the comment,
     *         or a 404 response if the review does not exist.
     */
    public function destroy(int $id): JsonResponse
    {
        $review = Review::find($id);

        Gate::authorize('removeComment', $review);

        if (! $review) {
            return response()->json([
                'message' => 'التقييم غير موجود.',
            ], 404);
        }

        // Remove the customer's comment while keeping the review.
        $review->update([
            'comment' => null,
        ]);

        return response()->json([
            'message' => 'تم حذف تعليق التقييم بنجاح.',
        ], 200);
    }
}