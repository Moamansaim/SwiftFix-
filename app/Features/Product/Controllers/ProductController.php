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
     *
     * @return JsonResponse
     *
     * @hint Retrieves all products along with their related categories
     *        and returns them using the ProductResource collection.
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
     *
     * @param int $id
     *        The ID of the product to retrieve.
     *
     * @return JsonResponse
     *
     * @hint Finds the product using the provided ID and returns its
     *        details. A not-found exception is thrown if the product
     *        does not exist.
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
     *
     * @param ProductRequest $productRequest
     *        The validated product request data.
     *
     * @return JsonResponse
     *
     * @hint Creates a new product using the validated request data
     *        and returns a success response after the product is stored.
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
     *
     * @param ProductRequest $productRequest
     *        The validated product request data.
     *
     * @param int $id
     *        The ID of the product to update.
     *
     * @return JsonResponse
     *
     * @hint Finds the product using the provided ID, updates it with
     *        the validated request data, and returns a success response.
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
     *
     * @param int $id
     *        The ID of the product to delete.
     *
     * @return JsonResponse
     *
     * @hint Finds the product using the provided ID and permanently
     *        removes it from the database.
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