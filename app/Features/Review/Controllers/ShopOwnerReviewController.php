<?php

namespace App\Features\Review\Controllers;

use App\Features\Review\Models\Review;
use App\Features\Review\Requests\ReviewReplyRequest;
use App\Features\Review\Resources\ReviewResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ShopOwnerReviewController extends Controller
{
    /**
     * Get all reviews for the authenticated shop owner.
     *
     * @return JsonResponse
     *
     * @hint Retrieves the shop associated with the authenticated user
     *        and returns all reviews belonging to that shop, including
     *        the related customer data. The reviews are ordered from
     *        newest to oldest, along with the average rating and total
     *        number of reviews.
     */
    public function index(): JsonResponse
    {
        $user = Auth::guard('sanctum')->user();

        $shop = $user->shop;

        if (! $shop) {
            return response()->json([
                'message' => 'لا توجد ورشة مرتبطة بهذا المستخدم.',
            ], 404);
        }

        $reviews = Review::with('user')
            ->where('shop_id', $shop->id)
            ->latest()
            ->get();

        $averageRating = $reviews->avg('rating');

        return response()->json([
            'average_rating' => round($averageRating ?? 0, 1),
            'reviews_count' => $reviews->count(),
            'reviews' => ReviewResource::collection($reviews),
        ], 200);
    }

    /**
     * Reply to a review belonging to the authenticated shop.
     *
     * @param ReviewReplyRequest $request
     *        The validated request containing the shop owner's reply.
     *
     * @param int $id
     *        The ID of the review to reply to.
     *
     * @return JsonResponse
     *
     * @hint Verifies that the authenticated user has an associated shop
     *        and that the specified review belongs to that shop before
     *        saving the reply. The reply timestamp is also recorded,
     *        and the updated review is returned with the customer data.
     */
    public function reply(
        ReviewReplyRequest $request,
        int $id
    ): JsonResponse {
        $user = Auth::guard('sanctum')->user();

        $shop = $user->shop;

        if (! $shop) {
            return response()->json([
                'message' => 'لا توجد ورشة مرتبطة بهذا المستخدم.',
            ], 404);
        }

        $review = Review::where('id', $id)
            ->where('shop_id', $shop->id)
            ->first();

        if (! $review) {
            return response()->json([
                'message' => 'التقييم غير موجود أو لا ينتمي إلى ورشتك.',
            ], 404);
        }

        $review->update([
            'shop_reply' => $request->validated('reply'),
            'replied_at' => now(),
        ]);

        return response()->json([
            'message' => 'تم الرد على التقييم بنجاح.',
            'review' => new ReviewResource(
                $review->fresh()->load('user')
            ),
        ], 200);
    }
}