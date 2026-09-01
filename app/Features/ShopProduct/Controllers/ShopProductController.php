<?php

namespace App\Features\ShopProduct\Controllers;

use App\Features\ShopProduct\Models\ShopProduct;
use App\Features\ShopProduct\Requests\ShopProductRequest;
use App\Features\ShopProduct\Resources\ShopProductResource;
use App\Features\ShopProduct\Services\ImageProduct;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ShopProductController extends Controller
{
    /**
     * Get all shop products for the authenticated user's shop.
     */
    public function getAllShopProducts(): JsonResponse
    {
        $user = Auth::guard('sanctum')->user();

        $shop = $user->shop;

        if (! $shop) {
            return response()->json([
                'message' => 'لا توجد ورشة مرتبطة بهذا الحساب.',
            ], 404);
        }

        $products = ShopProduct::where('shop_id', $shop->id)
            ->with([
                'product.category',
                'deviceModel',
            ])
            ->get();

        return response()->json([
            'products' => ShopProductResource::collection($products),
        ], 200);
    }

    /**
     * Store a new shop product.
     */
    public function store(
        ShopProductRequest $shopProductRequest,
        ImageProduct $imageProduct
    ): JsonResponse {
        $user = Auth::guard('sanctum')->user();

        $shop = $user->shop;

        if (! $shop) {
            return response()->json([
                'message' => 'لا يوجد متجر مرتبط بهذا المستخدم.',
            ], 404);
        }

        $validated = $shopProductRequest->validated();

        if ($shopProductRequest->hasFile('image')) {
            $validated['image'] = $imageProduct->upload(
                $shopProductRequest->file('image'),
                'image-product'
            );
        }

        $validated['shop_id'] = $shop->id;

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
        int $id,
        ImageProduct $imageProduct
    ): JsonResponse {
        $user = Auth::guard('sanctum')->user();

        $shop = $user->shop;

        if (! $shop) {
            return response()->json([
                'message' => 'لا يوجد متجر مرتبط بهذا المستخدم.',
            ], 404);
        }

        $product = ShopProduct::where('id', $id)
            ->where('shop_id', $shop->id)
            ->firstOrFail();

        $validated = $shopProductRequest->validated();

        if ($shopProductRequest->hasFile('image')) {
            $validated['image'] = $imageProduct->replace(
                $shopProductRequest->file('image'),
                $product->image,
                'image-product'
            );
        }

        $product->update($validated);

        return response()->json([
            'message' => 'تم تعديل المنتج بنجاح.',
        ], 200);
    }

    /**
     * Delete a shop product.
     */
    public function destroy(
        int $id,
        ImageProduct $imageProduct
    ): JsonResponse {
        try {
            $shopProduct = ShopProduct::findOrFail($id);

            $imagePath = $shopProduct->image;

            $shopProduct->delete();

            if ($imagePath) {
                $imageProduct->delete($imagePath);
            }

            return response()->json([
                'message' => 'تم حذف المنتج بنجاح.',
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'فشل حذف المنتج، يرجى المحاولة لاحقًا.',
            ], 500);
        }
    }
}
