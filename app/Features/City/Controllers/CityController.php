<?php

namespace App\Features\City\Controllers;

use App\Features\City\Models\City;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class CityController extends Controller
{
    /**
     * Get all cities   .
     */
    public function getAllCities(int $id): JsonResponse
    {
        $cities = City::where('country_id', $id)
            ->select(['id', 'name'])
            ->get();

        return response()->json([
            'cities' => $cities,
        ], 200);
    }
}
