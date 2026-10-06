<?php

namespace App\Features\FeatureShop\Controllers;

use App\Features\FeatureShop\Models\FeatureShop;
use App\Features\FeatureShop\Requests\FeatureShopRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class FeatureShopController extends Controller
{
    /**
     * Get all feature shops.
     *
     * @return JsonResponse
     *
     * @hint Retrieves all feature shop records that belong
     *       to the shop of the currently authenticated user.
     */
    public function getAllFeaturesShop(): JsonResponse
    {

        $user = Auth::guard('sanctum')->user();

        $featuresShop = FeatureShop::where(
            'shop_id',
            $user->shop->id
        )->get();

        return response()->json([
            'featuresShop' => $featuresShop,
        ], 200);
    }

    /**
     * Store a new feature shop.
     *
     * @param FeatureShopRequest $featureShopRequest
     *        The validated feature shop data.
     *
     * @return JsonResponse
     *
     * @hint Creates a new feature shop using the validated
     *       feature data and the shop associated with the
     *       currently authenticated user.
     */
    public function store(
        FeatureShopRequest $featureShopRequest
    ): JsonResponse {
       
        Gate::authorize('create', FeatureShop::class);

        $user = Auth::guard('sanctum')->user();

        $validated = $featureShopRequest->validated();

        FeatureShop::create([
            'shop_id' => $user->shop->id,
            'feature' => $validated['feature'],
        ]);

        return response()->json([
            'message' => 'تمت إضافة الميزة بنجاح.',
        ], 201);
    }

    /**
     * Update an existing feature shop.
     *
     * @param FeatureShopRequest $featureShopRequest
     *        The validated feature shop data.
     *
     * @param int $id
     *        The ID of the feature shop to update.
     *
     * @return JsonResponse
     *
     * @hint Finds the specified feature shop and updates it
     *       using the validated request data.
     */
    public function update(
        FeatureShopRequest $featureShopRequest,
        int $id
    ): JsonResponse {
        $featureShop = FeatureShop::findOrFail($id);

        Gate::authorize('update', $featureShop);

        $featureShop->update(
            $featureShopRequest->validated()
        );

        return response()->json([
            'message' => 'تم تعديل الميزة بنجاح.',
        ], 200);
    }

    /**
     * Delete a feature shop.
     *
     * @param int $id
     *        The ID of the feature shop to delete.
     *
     * @return JsonResponse
     *
     * @hint Finds and deletes the specified feature shop.
     */
    public function destroy(int $id): JsonResponse
    {
        $featureShop = FeatureShop::findOrFail($id);

        Gate::authorize('delete', $featureShop);

        $featureShop->delete();

        return response()->json([
            'message' => 'تم حذف الميزة بنجاح.',
        ], 200);
    }
}