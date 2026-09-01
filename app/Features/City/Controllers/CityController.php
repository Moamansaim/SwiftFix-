<?php

namespace App\Features\City\Controllers;

use App\Features\City\Models\City;
use App\Features\City\Requests\CityRequest;
use App\Features\Country\Models\Country;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class CityController extends Controller
{
    /**
     * Get all cities for a specific country.
     */
    public function getCitiesByCountry(int $id): JsonResponse
    {
        $cities = City::where('country_id', $id)
            ->select(['id', 'name'])
            ->get();

        return response()->json([
            'cities' => $cities,
        ], 200);
    }

    /**
     * Get all cities with all properties.
     */
    public function getAllCities(): JsonResponse
    {
        $cities = City::all();

        return response()->json([
            'cities' => $cities,
        ], 200);
    }

    /**
     * Store a new city.
     */
    public function store(CityRequest $cityRequest): JsonResponse
    {
        $validated = $cityRequest->validated();

        City::create($validated);

        return response()->json([
            'message' => 'تمت إضافة المدينة بنجاح.',
        ], 201);
    }

    /**
     * Update an existing city.
     */
    public function update(
        CityRequest $cityRequest,
        int $id
    ): JsonResponse {
        $city = City::findOrFail($id);

        $city->update(
            $cityRequest->validated()
        );

        return response()->json([
            'message' => 'تم تعديل المدينة بنجاح.',
        ], 200);
    }

    /**
     * Delete a city.
     */
    public function destroy(int $id): JsonResponse
    {
        $city = City::findOrFail($id);

        $city->delete();

        return response()->json([
            'message' => 'تم حذف المدينة بنجاح.',
        ], 200);
    }
}
