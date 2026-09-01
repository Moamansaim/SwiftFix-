<?php

namespace App\Features\FeatureShop\Controllers;


use App\Features\FeatureShop\Models\FeatureShop;
use App\Features\FeatureShop\Requests\FeatureShopRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class FeatureShopController extends Controller
{
    /**
     * Get all feature shop.
     */
    public function getAllFeaturesShop(): JsonResponse
    {
        $featuresShop = FeatureShop::all();

        return response()->json([
            'featuresShop' => $featuresShop,
        ], 200);
    }

    /**
     * Store a new feature shop.
     */
    public function store(FeatureShopRequest $featureShopRequest): JsonResponse
    {
        $validated = $featureShopRequest->validated();

        FeatureShop::create($validated);

        return response()->json([
            'message' => 'تمت إضافة الميزة بنجاح.',
        ], 201);
    }

    /**
     * Update an existing feature shop.
     */
    public function update(
        FeatureShopRequest $featureShopRequest,
        int $id
    ): JsonResponse {
        $featureShop = FeatureShop::findOrFail($id);

        $featureShop->update(
            $featureShopRequest->validated()
        );

        return response()->json([
            'message' => 'تم تعديل الميزة بنجاح.',
        ], 200);
    }

    /**
     * Delete a feature shop.
     */
    public function destroy(int $id): JsonResponse
    {
        $featureShop = FeatureShop::findOrFail($id);

        $featureShop->delete();

        return response()->json([
            'message' => 'تم حذف الميزة بنجاح.',
        ], 200);
    }
}
