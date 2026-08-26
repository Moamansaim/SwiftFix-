<?php

namespace App\Features\Services\Controllers;

use App\Features\Services\Models\Service;
use App\Http\Controllers\Controller;

class ServiceController extends Controller
{
    /**
     * جلب جميع الخدمات المتاحة من قاعدة البيانات وإرجاعها بصيغة JSON.
     */
    public function getAllServices()
    {
        $services = Service::all();

        return response()->json([
            'services' => $services,
        ], 200);
    }
}