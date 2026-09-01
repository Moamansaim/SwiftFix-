<?php

namespace App\Features\Brand\Controllers;

use App\Features\Brand\Models\Brand;
use App\Features\Brand\Requests\BrandRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class BrandController extends Controller
{
    /**
     * Get all brands.
     */
    public function getAllBrands(): JsonResponse
    {
        $brands = Brand::all();

        return response()->json([
            'brands' => $brands,
        ], 200);
    }

    /**
     * Get all brands for select.
     */
    public function getBrandsForSelect(): JsonResponse
    {
        $brands = Brand::select('id', 'brand_name')
            ->get();

        return response()->json([
            'brands' => $brands,
        ], 200);
    }

    /**
     * Get a brand with its devices.
     */
    public function show(int $id): JsonResponse
    {
        $brand = Brand::findOrFail($id);

        $deviceModels = $brand->deviceModels()
            ->select('id', 'device_model_name')
            ->get();

        return response()->json([
            'deviceModels' => $deviceModels,
        ], 200);
    }

    /**
     * Store a new brand.
     */
    public function store(BrandRequest $brandRequest): JsonResponse
    {
        Brand::create($brandRequest->validated());

        return response()->json([
            'message' => 'تمت إضافة العلامة التجارية بنجاح.',
        ], 201);
    }

    /**
     * Update an existing brand.
     */
    public function update(BrandRequest $brandRequest, int $id): JsonResponse
    {
        $brand = Brand::findOrFail($id);

        $brand->update($brandRequest->validated());

        return response()->json([
            'message' => 'تم تعديل العلامة التجارية بنجاح.',
        ], 200);
    }

    /**
     * Delete a brand.
     */
    public function destroy(int $id): JsonResponse
    {
        $brand = Brand::findOrFail($id);

        $brand->delete();

        return response()->json([
            'message' => 'تم حذف العلامة التجارية بنجاح.',
        ], 200);
    }
}
