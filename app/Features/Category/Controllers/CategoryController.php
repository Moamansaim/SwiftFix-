<?php

namespace App\Features\Category\Controllers;

use App\Features\Category\Models\Category;
use App\Features\Category\Requests\CategoryRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * Get all categories.
     */
    public function getAllCategories(): JsonResponse
    {
        $categories = Category::all();

        return response()->json([
            'categories' => $categories,
        ], 200);
    }

    /**
     * Get all categories for select.
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
     */
    public function store(CategoryRequest $categoryRequest): JsonResponse
    {
        Category::create($categoryRequest->validated());

        return response()->json([
            'message' => 'تمت إضافة الفئة بنجاح.',
        ], 201);
    }

    /**
     * Update an existing category.
     */
    public function update(
        CategoryRequest $categoryRequest,
        int $id
    ): JsonResponse {
        $category = Category::findOrFail($id);

        $category->update($categoryRequest->validated());

        return response()->json([
            'message' => 'تم تعديل الفئة بنجاح.',
        ], 200);
    }

    /**
     * Delete a category.
     */
    public function destroy(int $id): JsonResponse
    {
        $category = Category::findOrFail($id);

        $category->delete();

        return response()->json([
            'message' => 'تم حذف الفئة بنجاح.',
        ], 200);
    }
}
