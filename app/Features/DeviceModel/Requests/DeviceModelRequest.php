<?php

namespace App\Features\DeviceModel\Requests; 

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeviceModelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'device_model_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('device_models', 'device_model_name')
                    ->ignore($this->route('id')),
            ],
        ];
    }
}