<?php

namespace App\Features\DeviceModel\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class DeviceModelRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string|ValidationRule|Unique>>
     *
     * @hint Validates the device model name and the associated brand.
     *        The device model name must be unique while allowing
     *        the current device model during update.
     */
    public function rules(): array
    {
        return [
            'device_model_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('device_models', 'device_model_name')->ignore($this->route('id')),
            ],

            'brand_id' => [
                'required',
                'integer',
                'exists:brands,id',
            ],
        ];
    }
}