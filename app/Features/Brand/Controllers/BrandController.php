<?php

namespace App\Features\Brand\Controllers;

use App\Features\Brand\Models\Brand;
use App\Features\Brand\Requests\BrandRequest;
use App\Http\Controllers\Controller;


class BrandController extends Controller
{
    /**
     * جلب جميع العلامات التجارية.
     */
    public function getAllBrands()
    {
        $brands = Brand::all();

        return response()->json([
            'brands' => $brands,
        ], 200);
    }

    /**
     * إضافة علامة تجارية جديدة.
     */
    public function store(BrandRequest $brandRequest)
    {
        Brand::create($brandRequest->validated());

        return response()->json([
            'message' => 'تمت إضافة العلامة التجارية بنجاح.',
        ], 201);
    }

    /**
     * تعديل علامة تجارية.
     */
    public function update(BrandRequest $brandRequest, $id)
    {
        $brand = Brand::findOrFail($id);

        $brand->update($brandRequest->validated());

        return response()->json([
            'message' => 'تم تعديل العلامة التجارية بنجاح.',
        ], 200);
    }

    /**
     * حذف علامة تجارية.
     */
    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);

        $brand->delete();

        return response()->json([
            'message' => 'تم حذف العلامة التجارية بنجاح.',
        ], 200);
    }
}