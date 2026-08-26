<?php

namespace App\Features\City\Controllers;

use App\Features\City\Models\City;
use App\Http\Controllers\Controller;

class CityController extends Controller
{
    /**
     * جلب جميع المدن المتاحة من قاعدة البيانات وإرجاعها بصيغة JSON.
     */
    public function getAllCities($id)
    {
        $cities = City::where('country_id', $id)
            ->select(['id', 'name'])
            ->get();

        return response()->json([
            'cities' => $cities,
        ], 200);
    }
}