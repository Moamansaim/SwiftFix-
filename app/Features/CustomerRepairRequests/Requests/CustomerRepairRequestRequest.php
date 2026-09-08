<?php

namespace App\Features\CustomerRepairRequests\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRepairRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     *
     * @hint Only authenticated users can submit repair requests.
     */
    public function authorize(): bool
    {
        return auth('sanctum')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     *
     * @hint Validates the shop, device model, service,
     *        description, optional image, and customer address.
     */
    public function rules(): array
    {
        return [
            'shop_id' => [
                'required',
                'integer',
                'exists:shops,id',
            ],

            'device_model_id' => [
                'required',
                'integer',
                'exists:device_models,id',
            ],

            'service_id' => [
                'required',
                'integer',
                'exists:services,id',
            ],

            'description' => [
                'required',
                'string',
                'max:2000',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'address' => [
                'required',
                'string',
                'max:500',
            ],
        ];
    }
}