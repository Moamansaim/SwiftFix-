<?php

namespace App\Features\Review\Controllers;

use App\Features\Review\Models\Review;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class AdminReviewController extends Controller
{
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