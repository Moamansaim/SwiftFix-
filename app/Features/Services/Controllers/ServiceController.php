<?php

namespace App\Features\Services\Controllers;

use App\Features\Services\Models\Service;
use App\Features\Services\Requests\ServiceRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ServiceController extends Controller
{
    /**
     * Get all services  .
     */
    public function getAllServices(): JsonResponse
    {
        $services = Service::all();

        return response()->json([
            'services' => $services,
        ], 200);
    }

    /**
     * Get all services for select.
     */
    public function getServicesForSelect(): JsonResponse
    {
        $services = Service::select('id', 'service_name')
            ->get();

        return response()->json([
            'services' => $services,
        ], 200);
    }

    /**
     * Store a new service.
     */
    public function store(ServiceRequest $serviceRequest): JsonResponse
    {
        Service::create($serviceRequest->validated());

        return response()->json([
            'message' => 'تمت إضافة  الخدمة بنجاح.',
        ], 201);
    }

    /**
     * Update an existing service.
     */
    public function update(
        ServiceRequest $serviceRequest,
        int $id
    ): JsonResponse {
        $service = Service::findOrFail($id);

        $service->update(
            $serviceRequest->validated()
        );

        return response()->json([
            'message' => 'تم تعديل الخدمة بنجاح.',
        ], 200);
    }

    /**
     * Delete a service.
     */
    public function destroy(int $id): JsonResponse
    {
        $service = Service::findOrFail($id);

        $service->delete();

        return response()->json([
            'message' => 'تم حذف الخدمة بنجاح.',
        ], 200);
    }
}
