<?php

namespace App\Features\Country\Controllers;

use App\Features\Country\Models\Country;
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
}
