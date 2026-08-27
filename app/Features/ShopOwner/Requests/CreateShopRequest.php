<?php

namespace App\Features\ShopOwner\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateShopRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // الحماية عبر auth:sanctum على المسار
    }

    public function rules(): array
    {
        return [
            'shop_name'     => ['required', 'string', 'max:120'],
            'description' => ['required', 'string', 'max:1000'],   // كان nullable
            'cover_image'   => ['nullable', 'image', 'max:5120'],
            'country_id'    => ['required', 'integer', 'exists:countries,id'],
            'city_id'       => ['required', 'integer', 'exists:cities,id'],
            'district_id'   => ['required', 'integer', 'exists:districts,id'],
            'street'        => ['nullable', 'string', 'max:120'],
            'latitude'      => ['required', 'numeric', 'between:-90,90'],
            'longitude'     => ['required', 'numeric', 'between:-180,180'],
            'working_hours' => ['nullable', 'string', 'max:60'],
            'service_ids'   => ['required', 'array', 'min:1'],
            'service_ids.*' => ['integer', 'exists:services,id'],
        ];
    }
}