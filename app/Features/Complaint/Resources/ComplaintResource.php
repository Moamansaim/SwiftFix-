<?php

namespace App\Features\Complaint\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComplaintResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'customer' => [
                'id' => $this->user?->id,
                'name' => $this->user?->first_name . ' ' . $this->user?->last_name,
                'email' => $this->user?->email,
            ],

            'shop' => [
                'id' => $this->shop?->id,
                'name' => $this->shop?->shop_name,
            ],

            'subject' => $this->subject,

            'message' => $this->message,

            'status' => $this->status,

            'status_name' => $this->status === 'pending'
                ? 'قيد الانتظار'
                : 'تم الرد',

            'admin_reply' => $this->admin_reply,

            'replied_at' => $this->replied_at,

            'created_at' => $this->created_at,
        ];
    }
}