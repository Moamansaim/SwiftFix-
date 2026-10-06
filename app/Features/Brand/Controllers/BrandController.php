<?php

namespace App\Features\Brand\Controllers;

use App\Features\Brand\Models\Brand;
use App\Features\Brand\Requests\BrandRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

/**
 * Controller responsible for managing brands.
 *
 * This controller handles:
 * - Retrieving all brands.
 * - Retrieving brands in a lightweight format for select inputs.
 * - Retrieving a brand's device models.
 * - Creating a new brand.
 * - Updating an existing brand.
 * - Deleting a brand.
 */
class BrandController extends Controller
{

    /**
     * Get all brands.
     *
     * @return JsonResponse
     */
    public function getAllBrands(): JsonResponse
    {
        Gate::authorize('viewAny', Brand::class);

        $brands = Brand::all();

        return response()->json([
            'brands' => $brands,
        ], 200);
    }

    /**
     * Get all brands for a select input.
     *
     * @return JsonResponse
     */
    public function getBrandsForSelect(): JsonResponse
    {
        Gate::authorize('viewAny', Brand::class);

        $brands = Brand::select('id', 'brand_name')
            ->get();

        return response()->json([
            'brands' => $brands,
        ], 200);
    }

    /**
     * Get a brand with its device models.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $brand = Brand::findOrFail($id);

        Gate::authorize('viewAny', Brand::class);

        $deviceModels = $brand->deviceModels()
            ->select('id', 'device_model_name')
            ->get();

        return response()->json([
            'deviceModels' => $deviceModels,
        ], 200);
    }

    /**
     * Store a new brand.
     *
     * @param BrandRequest $brandRequest
     * @return JsonResponse
     */
    public function store(BrandRequest $brandRequest): JsonResponse
    {
        Gate::authorize('create', Brand::class);

        Brand::create($brandRequest->validated());

        return response()->json([
            'message' => 'تمت إضافة العلامة التجارية بنجاح.',
        ], 201);
    }

    /**
     * Update an existing brand.
     *
     * @param BrandRequest $brandRequest
     * @param int $id
     * @return JsonResponse
     */
    public function update(
        BrandRequest $brandRequest,
        int $id
    ): JsonResponse {
        $brand = Brand::findOrFail($id);

        Gate::authorize('update', $brand);

        $brand->update($brandRequest->validated());

        return response()->json([
            'message' => 'تم تعديل العلامة التجارية بنجاح.',
        ], 200);
    }

    /**
     * Delete a brand.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $brand = Brand::findOrFail($id);

        Gate::authorize('delete', $brand);

        $brand->delete();

        return response()->json([
            'message' => 'تم حذف العلامة التجارية بنجاح.',
        ], 200);
    }
}