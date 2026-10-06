<?php

namespace App\Features\Category\Controllers;

use App\Features\Category\Models\Category;
use App\Features\Category\Requests\CategoryRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

/**
 * Category Controller
 *
 * Handles HTTP requests related to product categories.
 *
 * Provides endpoints for:
 * - Retrieving all categories.
 * - Retrieving categories for select/dropdown fields.
 * - Retrieving products belonging to a specific category.
 * - Creating a new category.
 * - Updating an existing category.
 * - Deleting an existing category.
 */
class CategoryController extends Controller
{
    /**
     * Get all categories.
     *
     * @return JsonResponse
     */
    public function getAllCategories(): JsonResponse
    {
        Gate::authorize('viewAny', Category::class);

        $categories = Category::all();

        return response()->json([
            'categories' => $categories,
        ], 200);
    }

    /**
     * Get all categories for select.
     *
     * @return JsonResponse
     */
    public function getCategoriesForSelect(): JsonResponse
    {

        $categories = Category::select('id', 'category_name')
            ->get();

        return response()->json([
            'categories' => $categories,
        ], 200);
    }

    /**
     * Get products belonging to a category.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $category = Category::findOrFail($id);
       
        $products = $category->products()
            ->select('id', 'product_name')
            ->get();

        return response()->json([
            'products' => $products,
        ], 200);
    }

    /**
     * Store a new category.
     *
     * @param CategoryRequest $categoryRequest
     * @return JsonResponse
     */
    public function store(CategoryRequest $categoryRequest): JsonResponse
    {
        Gate::authorize('create', Category::class);

        Category::create($categoryRequest->validated());

        return response()->json([
            'message' => 'تمت إضافة الفئة بنجاح.',
        ], 201);
    }

    /**
     * Update an existing category.
     *
     * @param CategoryRequest $categoryRequest
     * @param int $id
     * @return JsonResponse
     */
    public function update(
        CategoryRequest $categoryRequest,
        int $id
    ): JsonResponse {
        $category = Category::findOrFail($id);

        Gate::authorize('update', $category);

        $category->update($categoryRequest->validated());

        return response()->json([
            'message' => 'تم تعديل الفئة بنجاح.',
        ], 200);
    }

    /**
     * Delete a category.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $category = Category::findOrFail($id);

        Gate::authorize('delete', $category);

        $category->delete();

        return response()->json([
            'message' => 'تم حذف الفئة بنجاح.',
        ], 200);
    }
}