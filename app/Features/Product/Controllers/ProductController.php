<?php

namespace App\Features\Product\Controllers;

use App\Features\Product\Models\Product;
use App\Features\Product\Requests\ProductRequest;
use App\Features\Product\Resources\ProductResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    /**

     * Get all products.
     */
    public function getAllProducts(): JsonResponse
    {
        $products = Product::with([
            'category',
        ])->get();

        return response()->json([
            'products' => ProductResource::collection($products),
        ], 200);
    }


    /**
     * Get a product by ID.
     */
    public function show(int $id): JsonResponse
    {
        $product = Product::findOrFail($id);

        return response()->json([
            'product' => $product,
        ], 200);
    }

    /**
     * Store a new product.
     */
    public function store(ProductRequest $productRequest): JsonResponse
    {
        Product::create($productRequest->validated());

        return response()->json([
            'message' => 'تمت إضافة المنتج بنجاح.',
        ], 201);
    }

    /**
     * Update an existing product.
     */
    public function update(
        ProductRequest $productRequest,
        int $id
    ): JsonResponse {
        $product = Product::findOrFail($id);

        $product->update($productRequest->validated());

        return response()->json([
            'message' => 'تم تعديل المنتج بنجاح.',
        ], 200);
    }

    /**
     * Delete a product.
     */
    public function destroy(int $id): JsonResponse
    {
        $product = Product::findOrFail($id);

        $product->delete();

        return response()->json([
            'message' => 'تم حذف المنتج بنجاح.',
        ], 200);
    }
}