<?php

namespace App\Features\City\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CityRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $cityId = $this->route('id');

        return [
            'country_id' => [
                'required',
                'integer',
                'exists:countries,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('cities', 'name')
                    ->where('country_id', $this->country_id)
                    ->ignore($cityId),
            ],
        ];
    }
}
