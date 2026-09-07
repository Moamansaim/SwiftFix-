<?php

namespace App\Features\Role\Controllers;

use App\Features\Brand\Requests\BrandRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;


class RoleController extends Controller
{

    public function getAllRoles(): JsonResponse
    {
        $roles = Role::all();

        return response()->json([
            'roles' => $roles,
        ], 200);
    }


    public function getBrandsForSelect(): JsonResponse
    {

        $roles = Role::select('id', 'name')
            ->get();

        return response()->json([
            'roles' => $roles,
        ], 200);
    }


    public function store(BrandRequest $brandRequest): JsonResponse
    {

        Role::create($brandRequest->validated());

        return response()->json([
            'message' => 'تمت إضافة الصلاحية بنجاح.',
        ], 201);
    }


    public function update(
        BrandRequest $brandRequest,
        int $id
    ): JsonResponse {

        $role = Role::findOrFail($id);

        $role->update($brandRequest->validated());

        return response()->json([
            'message' => 'تم تعديل الصلاحية  بنجاح.',
        ], 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $role = Role::findOrFail($id);

        $role->delete();

        return response()->json([
            'message' => 'تم حذف الصلاحية  بنجاح.',
        ], 200);
    }
}