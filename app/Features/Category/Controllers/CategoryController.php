<?php

namespace App\Features\Category\Controllers;

use App\Features\Category\Models\Category;
use App\Features\Category\Requests\CategoryRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

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
 * - Deleting a category.
 */
class CategoryController extends Controller
{
    /**
     * Get all categories.
     *
     * Retrieves all categories from the database and returns
     * them as a JSON response.
     *
     * @return JsonResponse
     */
    public function getAllCategories(): JsonResponse
    {
        // Retrieve all categories from the categories table.
        $categories = Category::all();

        return response()->json([
            'categories' => $categories,
        ], 200);
    }

    /**
     * Get all categories for select.
     *
     * Retrieves only the ID and name of each category.
     * This is useful for frontend select/dropdown fields
     * where the complete category data is not required.
     *
     * @return JsonResponse
     */
    public function getCategoriesForSelect(): JsonResponse
    {
        // Select only the fields required by the select input.
        $categories = Category::select('id', 'category_name')
            ->get();

        return response()->json([
            'categories' => $categories,
        ], 200);
    }

    /**
     * Get products belonging to a category.
     *
     * Finds the requested category and retrieves the products
     * associated with it.
     *
     * Only the product ID and product name are returned,
     * which is useful when displaying products related to
     * a selected category.
     *
     * @param int $id The category ID.
     *
     * @return JsonResponse
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *         If the category does not exist.
     */
    public function show(int $id): JsonResponse
    {
        // Find the category or automatically return a 404 response
        // if the category does not exist.
        $category = Category::findOrFail($id);

        // Retrieve products belonging to this category.
        // Only the required product fields are selected.
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
     * Validates the request data and creates a new category
     * using the validated attributes.
     *
     * @param CategoryRequest $categoryRequest The validated category request.
     *
     * @return JsonResponse
     */
    public function store(CategoryRequest $categoryRequest): JsonResponse
    {
        // Create a new category using only validated request data.
        Category::create($categoryRequest->validated());

        return response()->json([
            'message' => 'تمت إضافة الفئة بنجاح.',
        ], 201);
    }

    /**
     * Update an existing category.
     *
     * Finds the category by ID, validates the incoming data,
     * and updates the category with the validated attributes.
     *
     * @param CategoryRequest $categoryRequest The validated category request.
     * @param int $id The category ID.
     *
     * @return JsonResponse
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *         If the category does not exist.
     */
    public function update(
        CategoryRequest $categoryRequest,
        int $id
    ): JsonResponse {
        // Find the category or return a 404 response if it does not exist.
        $category = Category::findOrFail($id);

        // Update the category using only validated request data.
        $category->update($categoryRequest->validated());

        return response()->json([
            'message' => 'تم تعديل الفئة بنجاح.',
        ], 200);
    }

    /**
     * Delete a category.
     *
     * Finds the category by ID and permanently deletes it
     * according to the model's configured delete behavior.
     *
     * @param int $id The category ID.
     *
     * @return JsonResponse
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *         If the category does not exist.
     */
    public function destroy(int $id): JsonResponse
    {
        // Find the category or return a 404 response if it does not exist.
        $category = Category::findOrFail($id);

        // Delete the category from the database.
        $category->delete();

        return response()->json([
            'message' => 'تم حذف الفئة بنجاح.',
        ], 200);
    }
}