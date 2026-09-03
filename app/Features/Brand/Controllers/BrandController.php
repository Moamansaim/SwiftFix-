<?php

namespace App\Features\Brand\Controllers;

use App\Features\Brand\Models\Brand;
use App\Features\Brand\Requests\BrandRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

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
     * Retrieves all brand records from the database
     * and returns them as a JSON response.
     *
     * @return JsonResponse
     *         JSON response containing all brands.
     */
    public function getAllBrands(): JsonResponse
    {
        /*
         * Retrieve all brands from the brands table.
         */
        $brands = Brand::all();

        /*
         * Return the brands with HTTP status 200 (OK).
         */
        return response()->json([
            'brands' => $brands,
        ], 200);
    }

    /**
     * Get all brands for a select input.
     *
     * Retrieves only the ID and name of each brand.
     * This is useful when the frontend needs to display
     * brands inside a dropdown/select component without
     * loading unnecessary columns.
     *
     * @return JsonResponse
     *         JSON response containing the brand IDs and names.
     */
    public function getBrandsForSelect(): JsonResponse
    {
        /*
         * Select only the columns required by the select input.
         *
         * id:
         * Used as the value submitted by the frontend.
         *
         * brand_name:
         * Used as the label displayed to the user.
         */
        $brands = Brand::select('id', 'brand_name')
            ->get();

        /*
         * Return the brands with HTTP status 200 (OK).
         */
        return response()->json([
            'brands' => $brands,
        ], 200);
    }

    /**
     * Get a brand with its device models.
     *
     * Finds the requested brand and retrieves its related
     * device models.
     *
     * @param int $id
     *        The ID of the requested brand.
     *
     * @return JsonResponse
     *         JSON response containing the device models
     *         associated with the brand.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *         If the brand does not exist.
     */
    public function show(int $id): JsonResponse
    {
        /*
         * Find the brand by its primary key.
         *
         * findOrFail() automatically throws a 404 response
         * if the brand does not exist.
         */
        $brand = Brand::findOrFail($id);

        /*
         * Retrieve the device models associated with this brand.
         *
         * Only the ID and device model name are selected because
         * these are the fields required by the frontend.
         */
        $deviceModels = $brand->deviceModels()
            ->select('id', 'device_model_name')
            ->get();

        /*
         * Return the related device models with HTTP status 200.
         */
        return response()->json([
            'deviceModels' => $deviceModels,
        ], 200);
    }

    /**
     * Store a new brand.
     *
     * Validates the incoming request data and creates
     * a new brand in the database.
     *
     * @param BrandRequest $brandRequest
     *        FormRequest responsible for validating brand data.
     *
     * @return JsonResponse
     *         JSON response confirming successful creation.
     */
    public function store(BrandRequest $brandRequest): JsonResponse
    {
        /*
         * Get only the validated data from the request
         * and create a new brand using mass assignment.
         */
        Brand::create($brandRequest->validated());

        /*
         * Return HTTP status 201 (Created) to indicate
         * that the brand was successfully created.
         */
        return response()->json([
            'message' => 'تمت إضافة العلامة التجارية بنجاح.',
        ], 201);
    }

    /**
     * Update an existing brand.
     *
     * Finds the brand by ID, validates the incoming data,
     * and updates the brand record.
     *
     * @param BrandRequest $brandRequest
     *        FormRequest responsible for validating brand data.
     *
     * @param int $id
     *        The ID of the brand to update.
     *
     * @return JsonResponse
     *         JSON response confirming successful update.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *         If the brand does not exist.
     */
    public function update(
        BrandRequest $brandRequest,
        int $id
    ): JsonResponse {
        /*
         * Find the brand by its primary key.
         *
         * findOrFail() returns a 404 response automatically
         * if the brand does not exist.
         */
        $brand = Brand::findOrFail($id);

        /*
         * Update the brand using only validated request data.
         */
        $brand->update($brandRequest->validated());

        /*
         * Return HTTP status 200 (OK) after successful update.
         */
        return response()->json([
            'message' => 'تم تعديل العلامة التجارية بنجاح.',
        ], 200);
    }

    /**
     * Delete a brand.
     *
     * Finds the brand by ID and permanently deletes the
     * corresponding record from the database.
     *
     * @param int $id
     *        The ID of the brand to delete.
     *
     * @return JsonResponse
     *         JSON response confirming successful deletion.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *         If the brand does not exist.
     */
    public function destroy(int $id): JsonResponse
    {
        /*
         * Find the brand by its primary key.
         *
         * findOrFail() automatically returns a 404 response
         * if the brand does not exist.
         */
        $brand = Brand::findOrFail($id);

        /*
         * Delete the brand from the database.
         */
        $brand->delete();

        /*
         * Return HTTP status 200 (OK) after successful deletion.
         */
        return response()->json([
            'message' => 'تم حذف العلامة التجارية بنجاح.',
        ], 200);
    }
}