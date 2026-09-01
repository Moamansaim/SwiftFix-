<?php

namespace App\Features\Review\Controllers;

use App\Features\Review\Models\Review;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class AdminReviewController extends Controller
{
    /**
     * Delete a review by admin.
     */
    public function destroy(int $id): JsonResponse
    {
        $review = Review::find($id);

        if (! $review) {
            return response()->json([
                'message' => 'التقييم غير موجود.',
            ], 404);
        }

        $review->delete();

        return response()->json([
            'message' => 'تم حذف التقييم بنجاح.',
        ], 200);
    }
}