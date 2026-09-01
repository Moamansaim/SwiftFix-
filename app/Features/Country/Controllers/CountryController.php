<?php

namespace App\Features\Country\Controllers;

use App\Features\Country\Models\Country;
use App\Features\Country\Requests\CountryRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class CountryController extends Controller
{
    /**
     * Get all countries.
     */
    public function getAllCountries(): JsonResponse
    {
        $countries = Country::all();

        return response()->json([
            'countries' => $countries,
        ], 200);
    }

    /**
     * Get all countries for select.
     */
    public function getCountriesForSelect(): JsonResponse
    {
        $countries = Country::select('id', 'name')
            ->get();

        return response()->json([
            'countries' => $countries,
        ], 200);
    }

    /**
     * Store a new country.
     */
    public function store(CountryRequest $countryRequest): JsonResponse
    {
        $validated = $countryRequest->validated();

        Country::create($validated);

        return response()->json([
            'message' => 'تمت إضافة الدولة بنجاح.',
        ], 201);
    }

    /**
     * Update an existing country.
     */
    public function update(
        CountryRequest $countryRequest,
        int $id
    ): JsonResponse {
        $country = Country::findOrFail($id);

        $country->update(
            $countryRequest->validated()
        );

        return response()->json([
            'message' => 'تم تعديل الدولة بنجاح.',
        ], 200);
    }

    /**
     * Delete a country.
     */
    public function destroy(int $id): JsonResponse
    {
        $country = Country::findOrFail($id);

        $country->delete();

        return response()->json([
            'message' => 'تم حذف الدولة بنجاح.',
        ], 200);
    }
}
