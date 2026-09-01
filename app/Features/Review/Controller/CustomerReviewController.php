<?php

namespace App\Features\Review\Controllers;

use App\Features\Review\Models\Review;
use App\Features\Review\Requests\ReviewRequest;
use App\Features\Review\Resources\ReviewResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CustomerReviewController extends Controller
{
    /**
     * Add a new review to a shop.
     */
    public function store(
        ReviewRequest $request,
        int $shopId
    ): JsonResponse {
        $user = Auth::guard('sanctum')->user();

        $reviewExists = Review::where('user_id', $user->id)
            ->where('shop_id', $shopId)
            ->exists();

        if ($reviewExists) {
            return response()->json([
                'message' => 'لقد قمت بتقييم هذه الورشة مسبقًا.',
            ], 422);
        }

        $review = Review::create([
            'user_id' => $user->id,
            'shop_id' => $shopId,
            'rating' => $request->validated('rating'),
            'comment' => $request->validated('comment'),
        ]);

        return response()->json([
            'message' => 'تم إضافة التقييم بنجاح.',
            'review' => new ReviewResource(
                $review->load('user')
            ),
        ], 201);
    }

    /**
     * Update the current user's review.
     */
    public function update(
        ReviewRequest $request,
        int $id
    ): JsonResponse {
        $user = Auth::guard('sanctum')->user();

        $review = Review::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (! $review) {
            return response()->json([
                'message' => 'التقييم غير موجود أو لا تملكه.',
            ], 404);
        }

        $review->update([
            'rating' => $request->validated('rating'),
            'comment' => $request->validated('comment'),
        ]);

        return response()->json([
            'message' => 'تم تحديث التقييم بنجاح.',
        ], 200);
    }

    /**
     * Delete the current user's review.
     */
    public function destroy(int $id): JsonResponse
    {
        $user = Auth::guard('sanctum')->user();

        $review = Review::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (! $review) {
            return response()->json([
                'message' => 'التقييم غير موجود أو لا تملكه.',
            ], 404);
        }

        $review->delete();

        return response()->json([
            'message' => 'تم حذف التقييم بنجاح.',
        ], 200);
    }
}
