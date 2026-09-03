<?php

namespace App\Features\DeviceModel\Controllers;

use App\Features\DeviceModel\Models\DeviceModel;
use App\Features\DeviceModel\Requests\DeviceModelRequest;
use App\Features\DeviceModel\Resources\DeviceModelResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class DeviceModelController extends Controller
{
    /**
     * Get all device models.
     */
    public function getAllDeviceModels(): JsonResponse
    {
        $deviceModels = DeviceModel::with('brand')->get();

        return response()->json([
            'device_models' => DeviceModelResource::collection($deviceModels),
        ], 200);
    }

    /**
     * Store a new device model.
     */
    public function store(DeviceModelRequest $deviceModelRequest): JsonResponse
    {
        DeviceModel::create($deviceModelRequest->validated());

        return response()->json([
            'message' => 'تمت إضافة الجهاز بنجاح.',
        ], 201);
    }

    /**
     * Update an existing device model.
     */
    public function update(DeviceModelRequest $deviceModelRequest, int $id): JsonResponse
    {
        $deviceModel = DeviceModel::findOrFail($id);

        $deviceModel->update($deviceModelRequest->validated());

        return response()->json([
            'message' => 'تم تعديل بيانات الجهاز بنجاح.',
        ], 200);
    }

    /**
     * Delete a device model.
     */
    public function destroy(int $id): JsonResponse
    {
        $deviceModel = DeviceModel::findOrFail($id);

        $deviceModel->delete();

        return response()->json([
            'message' => 'تم حذف  الجهاز بنجاح.',
        ], 200);
    }
}