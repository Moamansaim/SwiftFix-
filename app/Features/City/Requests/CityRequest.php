<?php

namespace App\Features\City\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * City Request
 *
 * Handles validation rules for creating and updating cities.
 *
 * The same request class can be used for both store and update operations.
 */
class CityRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * Validates the country ID and city name.
     *
     * The city name must be unique within the selected country.
     * During an update operation, the current city is ignored
     * from the uniqueness check.
     *
     * @return array<string, array<int, string|\Illuminate\Validation\Rules\Unique>>
     */
    public function rules(): array
    {
        // Retrieve the city ID from the route.
        // This is used to exclude the current city during updates.
        $cityId = $this->route('id');

        return [
            'country_id' => [
                // The country ID is required.
                'required',

                // The country ID must be an integer.
                'integer',

                // The selected country must exist in the countries table.
                'exists:countries,id',
            ],

            'name' => [
                // The city name is required.
                'required',

                // The city name must be a string.
                'string',

                // The city name cannot exceed 255 characters.
                'max:255',

                // The city name must be unique within the selected country.
                // The current city is ignored when updating.
                Rule::unique('cities', 'name')
                    ->where('country_id', $this->country_id)
                    ->ignore($cityId),
            ],
        ];
    }
}