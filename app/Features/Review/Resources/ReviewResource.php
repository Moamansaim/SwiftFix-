<?php

namespace App\Features\Review\Resources;

use App\Features\Review\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Review
 */
class ReviewResource extends JsonResource
{
    /**
     * Transform the review resource into an array.
     *
     * @param Request $request
     *        The current HTTP request instance.
     *
     * @return array<string, mixed>
     *
     * @hint Returns the review details including the review ID,
     *        customer information, rating, comment, shop reply,
     *        reply timestamp, and review creation date.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->first_name . ' ' . $this->user->last_name,
            ],

            'rating' => $this->rating,
            'comment' => $this->comment,
            'shop_reply' => $this->shop_reply,
            'replied_at' => $this->replied_at,
            'created_at' => $this->created_at,
        ];
    }
}