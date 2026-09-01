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
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->first_name.' '.$this->user->last_name,
            ],

            'rating' => $this->rating,

            'comment' => $this->comment,

            'shop_reply' => $this->shop_reply,

            'replied_at' => $this->replied_at,

            'created_at' => $this->created_at,
        ];
    }
}
