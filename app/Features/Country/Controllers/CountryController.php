<?php

namespace App\Features\Country\Controllers;

use App\Features\Country\Models\Country;
use App\Http\Controllers\Controller;

class CountryController extends Controller
{
    /**
     * جلب جميع الدول المتاحة من قاعدة البيانات وإرجاعها بصيغة JSON.
     */
    public function getAllCountries()
    {
        $countries = Country::all();

        return response()->json([
            'countries' => $countries,
        ], 200);
    }
}