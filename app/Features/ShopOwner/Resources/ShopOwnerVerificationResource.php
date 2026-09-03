<?php

namespace App\Features\ShopOwner\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** * @mixin ShopOwnerVerification */
class ShopOwnerVerificationResource extends JsonResource
{
    /** * @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->first_name.' '.$this->last_name,
            'email' => $this->email,
            'national_id_image' => $this->national_id_image,
            'commercial_record_image' => $this->commercial_record_image,
            'phone_number' => $this->phone_number,
            'country_name' => $this->country->name,
            'status' => $this->status,
            'notes' => $this->notes,
            'reviewed_by' => $this->reviewed_by,
            'reviewed_at' => $this->reviewed_at,
            'created_at' => $this->created_at,
            'services' => $this->services->map(function ($service) {
                return [
                    'service_name' => $service->service_name,
                ];
            }),
        ];
    }
}
