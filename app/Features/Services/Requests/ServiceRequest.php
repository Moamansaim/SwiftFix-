<?php

namespace App\Features\Services\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'service_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('countries', 'service_name')
                    ->ignore($this->route('id')),
            ],
        ];
    }
}
