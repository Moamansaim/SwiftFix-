<?php

namespace App\Features\Districts\Controllers;

use App\Features\Districts\Models\Districts;
use App\Http\Controllers\Controller;

class DistrictsController extends Controller
{
    /**
     * جلب جميع الأحياء المتاحة من قاعدة البيانات وإرجاعها بصيغة JSON.
     */
    public function getAllDistricts($id)
    {
        $districts = Districts::where('city_id', $id)
            ->select(['id', 'name'])
            ->get();

        return response()->json([
            'districts' => $districts,
        ], 200);
    }
}