<?php

namespace App\Features\Country\Controllers;

use App\Features\Country\Models\Country;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

/**
 * Country Controller
 *
 * Handles HTTP requests related to countries.
 *
 * Provides endpoints for:
 * - Retrieving all countries.
 * - Retrieving countries for select/dropdown fields.
 * - Creating a new country.
 * - Updating an existing country.
 * - Deleting a country.
 */
class CountryController extends Controller
{
    /**
     * Get all countries.
     *
     * Retrieves all countries from the database and returns
     * them as a JSON response.
     *
     * @return JsonResponse
     */
    public function getAllCountries(): JsonResponse
    {
        Gate::authorize('viewAny', Country::class);
        
        // Retrieve all countries from the database.
        $countries = Country::all();

        return response()->json([
            'countries' => $countries,
        ], 200);
    }

    /**
     * Get all countries for select.
     *
     * Retrieves only the ID and name of each country.
     * This is useful for frontend select/dropdown fields
     * where the complete country data is not required.
     *
     * @return JsonResponse
     */
    public function getCountriesForSelect(): JsonResponse
    {
        // Select only the fields required by the select input.
        $countries = Country::select('id', 'name')
            ->get();

        return response()->json([
            'countries' => $countries,
        ], 200);
    }

    /**
     * Store a new country.
     *
     * Validates the incoming country data and creates
     * a new country using only the validated attributes.
     *
     * The country name must be unique in the countries table.
     *
     * @param Request $request The incoming HTTP request.
     *
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        Gate::authorize('create', Country::class);
        
        // Validate the incoming country data.
        $validated = $request->validate([
            'name' => [
                // The country name is required.
                'required',

                // The country name must be a string.
                'string',

                // The country name cannot exceed 255 characters.
                'max:255',

                // The country name must be unique.
                'unique:countries,name',
            ],
        ], [
            // Custom validation messages for the country name.
            'name.required' => 'اسم الدولة مطلوب.',
            'name.string' => 'اسم الدولة يجب أن يكون نصًا.',
            'name.max' => 'اسم الدولة يجب ألا يتجاوز 255 حرفًا.',
            'name.unique' => 'هذه الدولة موجودة بالفعل.',
        ]);

        // Create the country using only validated data.
        Country::create($validated);

        return response()->json([
            'message' => 'تمت إضافة الدولة بنجاح.',
        ], 201);
    }

    /**
     * Update an existing country.
     *
     * Finds the country by ID, validates the updated data,
     * and updates the country using the validated attributes.
     *
     * The current country is excluded from the uniqueness check
     * so that it can retain its existing name.
     *
     * @param Request $request The incoming HTTP request.
     * @param int $id The country ID.
     *
     * @return JsonResponse
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *         If the country does not exist.
     */
    public function update(
        Request $request,
        int $id
    ): JsonResponse {
        // Find the country or return a 404 response if it does not exist.
        $country = Country::findOrFail($id);

        Gate::authorize('update', $country);

        // Validate the updated country data.
        $validated = $request->validate([
            'name' => [
                // The country name is required.
                'required',

                // The country name must be a string.
                'string',

                // The country name cannot exceed 255 characters.
                'max:255',

                // The country name must be unique.
                // The current country is ignored during the check.
                Rule::unique('countries', 'name')
                    ->ignore($country->id),
            ],
        ], [
            // Custom validation messages for the country name.
            'name.required' => 'اسم الدولة مطلوب.',
            'name.string' => 'اسم الدولة يجب أن يكون نصًا.',
            'name.max' => 'اسم الدولة يجب ألا يتجاوز 255 حرفًا.',
            'name.unique' => 'هذه الدولة موجودة بالفعل.',
        ]);

        // Update the country using only validated data.
        $country->update($validated);

        return response()->json([
            'message' => 'تم تعديل الدولة بنجاح.',
        ], 200);
    }

    /**
     * Delete a country.
     *
     * Finds the country by ID and deletes it from the database.
     *
     * @param int $id The country ID.
     *
     * @return JsonResponse
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *         If the country does not exist.
     */
    public function destroy(int $id): JsonResponse
    {
        // Find the country or return a 404 response if it does not exist.
        $country = Country::findOrFail($id);

        Gate::authorize('delete', $country);

        // Delete the country from the database.
        $country->delete();

        return response()->json([
            'message' => 'تم حذف الدولة بنجاح.',
        ], 200);
    }
}