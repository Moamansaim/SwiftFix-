<?php

namespace App\Features\City\Controllers;

use App\Features\City\Models\City;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * City Controller
 *
 * Handles HTTP requests related to cities.
 *
 * Provides endpoints for:
 * - Retrieving cities belonging to a specific country.
 * - Retrieving all cities with their associated country names.
 * - Creating a new city.
 * - Updating an existing city.
 * - Deleting a city.
 */
class CityController extends Controller
{
    /**
     * Get all cities for a specific country.
     *
     * Retrieves cities that belong to the given country ID.
     * Only the city ID and name are returned.
     *
     * @param int $id The country ID.
     *
     * @return JsonResponse
     */
    public function getCitiesByCountry(int $id): JsonResponse
    {
        // Retrieve cities belonging to the specified country.
        $cities = City::where('country_id', $id)
            ->select(['id', 'name'])
            ->get();

        return response()->json([
            'cities' => $cities,
        ], 200);
    }

    /**
     * Get all cities.
     *
     * Retrieves all cities together with the name of their
     * associated country.
     *
     * A SQL JOIN is used to combine the cities and countries
     * tables through the country_id foreign key.
     *
     * @return JsonResponse
     */
    public function getAllCities(): JsonResponse
    {
        // Join cities with countries to include the country name
        // in the response.
        $cities = City::join(
            'countries',
            'cities.country_id',
            '=',
            'countries.id'
        )
            ->select(
                'cities.id',
                'cities.name',
                'cities.country_id',
                'countries.name as country_name',
                'cities.created_at',
                'cities.updated_at'
            )
            ->get();

        return response()->json([
            'cities' => $cities,
        ], 200);
    }

    /**
     * Store a new city.
     *
     * Validates the country ID and city name before creating
     * a new city in the database.
     *
     * The city name must be unique within the selected country.
     * This allows cities with the same name to exist in different
     * countries.
     *
     * @param Request $request The incoming HTTP request.
     *
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // Validate the incoming city data.
        $validated = $request->validate([
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
                Rule::unique('cities', 'name')
                    ->where('country_id', $request->country_id),
            ],
        ], [
            // Custom validation messages for the city name.
            'name.required' => 'اسم المدينة مطلوب.',
            'name.string' => 'اسم المدينة يجب أن يكون نصًا.',
            'name.max' => 'اسم المدينة يجب ألا يتجاوز 255 حرفًا.',
            'name.unique' => 'هذه المدينة موجودة بالفعل في الدولة المحددة.',
        ]);

        // Create the city using only validated data.
        City::create($validated);

        return response()->json([
            'message' => 'تمت إضافة المدينة بنجاح.',
        ], 201);
    }

    /**
     * Update an existing city.
     *
     * Finds the city by ID, validates the updated data,
     * and updates the city using the validated attributes.
     *
     * The city name must remain unique within the selected country,
     * while the current city is excluded from the uniqueness check.
     *
     * @param Request $request The incoming HTTP request.
     * @param int $id The city ID.
     *
     * @return JsonResponse
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *         If the city does not exist.
     */
    public function update(
        Request $request,
        int $id
    ): JsonResponse {
        // Find the city or return a 404 response if it does not exist.
        $city = City::findOrFail($id);

        // Validate the updated city data.
        $validated = $request->validate([
            'country_id' => [
                // The country ID is required.
                'required',

                // The country ID must be an integer.
                'integer',

                // The selected country must exist.
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
                // The current city is ignored during the uniqueness check.
                Rule::unique('cities', 'name')
                    ->where('country_id', $request->country_id)
                    ->ignore($city->id),
            ],
        ], [
            // Custom validation messages for the city name.
            'name.required' => 'اسم المدينة مطلوب.',
            'name.string' => 'اسم المدينة يجب أن يكون نصًا.',
            'name.max' => 'اسم المدينة يجب ألا يتجاوز 255 حرفًا.',
            'name.unique' => 'هذه المدينة موجودة بالفعل في الدولة المحددة.',
        ]);

        // Update the city using only validated data.
        $city->update($validated);

        return response()->json([
            'message' => 'تم تعديل المدينة بنجاح.',
        ], 200);
    }

    /**
     * Delete a city.
     *
     * Finds the city by ID and deletes it from the database.
     *
     * @param int $id The city ID.
     *
     * @return JsonResponse
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *         If the city does not exist.
     */
    public function destroy(int $id): JsonResponse
    {
        // Find the city or return a 404 response if it does not exist.
        $city = City::findOrFail($id);

        // Delete the city from the database.
        $city->delete();

        return response()->json([
            'message' => 'تم حذف المدينة بنجاح.',
        ], 200);
    }
}