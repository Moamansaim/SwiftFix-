<?php

namespace App\Features\Review\Policies;

use App\Features\Auth\Models\User;
use App\Features\Review\Models\Review;

class ReviewPolicy
{
    /**
     * Determine whether the administrator can remove
     * the customer's comment from the review.
     */
    public function removeComment(
        User $user,
        Review $review
    ): bool {
        return $user->can('حذف تعليق تقييم');
    }


    /**
     * Determine whether the user can view
     * reviews of all shops.
     */
    public function viewAllShopReviews(User $user): bool
    {
        return $user->can('عرض تقييمات جميع الورش');
    }

    /**
     * Determine whether the shop owner can view
     * the reviews of their shop.
     */
    public function viewShopReviews(User $user): bool
    {
        return $user->can('عرض تقييمات الورشة');
    }

    /**
     * Determine whether the shop owner can reply
     * to a review belonging to their shop.
     */
    public function reply(
        User $user,
        Review $review
    ): bool {
        return $user->can('الرد على التقييم');
    }
}