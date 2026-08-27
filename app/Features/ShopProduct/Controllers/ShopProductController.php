<?php

namespace App\Features\ShopProduct\Controllers;

use App\Features\ShopProduct\Models\ShopProduct;
use App\Features\ShopProduct\Requests\ShopProductRequest;
use App\Features\ShopProduct\Resources\ShopProductResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ShopProductController extends Controller
{
    /**
     * Get all shop products.
     */
    public function getAllShopProducts(): JsonResponse
    {
        $products = ShopProduct::with([
            'product.category',
            'product.deviceModel',
        ])->get();

        return response()->json([
            'products' => ShopProductResource::collection($products),
        ], 200);
    }

    /**
     * Store a new shop product.
     */
    public function store(ShopProductRequest $shopProductRequest): JsonResponse
    {

        $user = Auth::guard('sanctum')->user();

        $shod_id = $user->shops->id;

        $validated = $shopProductRequest->validated();

        if ($shopProductRequest->hasFile('image')) {
            $validated['image'] = $shopProductRequest->file('image')
                ->store('image-product', 'public');
        }

        $validated['shop_id'] = $shod_id;

        ShopProduct::create($validated);

        return response()->json([
            'message' => 'تمت إضافة المنتج بنجاح.',
        ], 201);
    }

    /**
     * Update an existing shop product.
     */
    public function update(
        ShopProductRequest $shopProductRequest,
        int $id
    ): JsonResponse {
        $product = ShopProduct::findOrFail($id);

        $product->update(
            $shopProductRequest->validated()
        );

        return response()->json([
            'message' => 'تم تعديل المنتج بنجاح.',
        ], 200);
    }

    /**
     * Delete a shop product.
     */
    public function destroy(int $id): JsonResponse
    {
        $product = ShopProduct::findOrFail($id);

        $product->delete();

        return response()->json([
            'message' => 'تم حذف المنتج بنجاح.',
        ], 200);
    }
}