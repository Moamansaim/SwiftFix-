<?php

namespace App\Features\Role\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * The guard name used for roles and permissions.
     */
    private const GUARD_NAME = 'web';

    /**
     * Get all roles.
     *
     * Retrieves all roles from the database.
     *
     * @return JsonResponse
     *
     * @hint Route:
     *        GET /api/roles
     */
    public function getAllRoles(): JsonResponse
    {
        $roles = Role::where(
            'guard_name',
            self::GUARD_NAME
        )->get();

        return response()->json([
            'roles' => $roles,
        ], 200);
    }

    /**
     * Get roles for select inputs.
     *
     * Retrieves only the ID and name of each role.
     *
     * @return JsonResponse
     *
     * @hint Route:
     *        GET /api/roles/select
     */
    public function getRolesForSelect(): JsonResponse
    {
        $roles = Role::where(
            'guard_name',
            self::GUARD_NAME
        )
            ->select('id', 'name')
            ->get();

        return response()->json([
            'roles' => $roles,
        ], 200);
    }

    /**
     * Store a new role.
     *
     * Creates a new role with a fixed guard name.
     * The role name must be unique.
     *
     * @param Request $request
     * @return JsonResponse
     *
     * @hint Route:
     *        POST /api/roles
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate(
            [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('roles', 'name')
                        ->where('guard_name', self::GUARD_NAME),
                ],
            ],
            [
                'name.required' => 'اسم الدور مطلوب.',
                'name.string' => 'اسم الدور يجب أن يكون نصًا.',
                'name.max' => 'اسم الدور يجب ألا يتجاوز 255 حرفًا.',
                'name.unique' => 'اسم الدور مستخدم بالفعل.',
            ]
        );

        Role::create([
            'name' => $validated['name'],
            'guard_name' => self::GUARD_NAME,
        ]);

        return response()->json([
            'message' => 'تمت إضافة الدور بنجاح.',
        ], 201);
    }

    /**
     * Update an existing role.
     *
     * Updates the role name while keeping the guard name fixed.
     * The role name must remain unique.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     *
     * @hint Route:
     *        PUT /api/roles/{id}
     */
    public function update(
        Request $request,
        int $id
    ): JsonResponse {
        $role = Role::where(
            'guard_name',
            self::GUARD_NAME
        )->findOrFail($id);

        $validated = $request->validate(
            [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('roles', 'name')
                        ->where('guard_name', self::GUARD_NAME)
                        ->ignore($role->id),
                ],
            ],
            [
                'name.required' => 'اسم الدور مطلوب.',
                'name.string' => 'اسم الدور يجب أن يكون نصًا.',
                'name.max' => 'اسم الدور يجب ألا يتجاوز 255 حرفًا.',
                'name.unique' => 'اسم الدور مستخدم بالفعل.',
            ]
        );

        $role->update([
            'name' => $validated['name'],
            'guard_name' => self::GUARD_NAME,
        ]);

        return response()->json([
            'message' => 'تم تعديل الدور بنجاح.',
        ], 200);
    }

    /**
     * Delete a role.
     *
     * Finds the role by ID and deletes it.
     *
     * @param int $id
     * @return JsonResponse
     *
     * @hint Route:
     *        DELETE /api/roles/{id}
     */
    public function destroy(int $id): JsonResponse
    {
        $role = Role::where(
            'guard_name',
            self::GUARD_NAME
        )->findOrFail($id);

        $role->delete();

        return response()->json([
            'message' => 'تم حذف الدور بنجاح.',
        ], 200);
    }

    /**
     * Show all permissions for a specific role.
     *
     * Retrieves all permissions available in the system
     * and indicates whether each permission is assigned
     * to the selected role.
     *
     * @param int $roleId
     * @return JsonResponse
     *
     * @hint Route:
     *        GET /api/roles/{roleId}/permissions
     */
    public function showPermissions(int $roleId): JsonResponse
    {
        $role = Role::where(
            'guard_name',
            self::GUARD_NAME
        )->findOrFail($roleId);

        $permissions = Permission::where(
            'guard_name',
            self::GUARD_NAME
        )
            ->get()
            ->map(function (Permission $permission) use ($role) {
                return [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'assigned' => $role->hasPermissionTo($permission),
                ];
            });

        return response()->json([
            'role' => [
                'id' => $role->id,
                'name' => $role->name,
            ],
            'permissions' => $permissions,
        ], 200);
    }

    /**
     * Give a permission to a role.
     *
     * Assigns the specified permission to the specified role.
     *
     * @param int $roleId
     * @param int $permissionId
     * @return JsonResponse
     *
     * @hint Route:
     *        POST /api/roles/{roleId}/permissions/{permissionId}
     */
    public function givePermission(
        int $roleId,
        int $permissionId
    ): JsonResponse {
        $role = Role::where(
            'guard_name',
            self::GUARD_NAME
        )->findOrFail($roleId);

        $permission = Permission::where('id', $permissionId)
            ->where('guard_name', self::GUARD_NAME)
            ->firstOrFail();

        if ($role->hasPermissionTo($permission)) {
            return response()->json([
                'message' => 'هذه الصلاحية مضافة بالفعل إلى هذا الدور.',
            ], 422);
        }

        $role->givePermissionTo($permission);

        return response()->json([
            'message' => 'تمت إضافة الصلاحية إلى الدور بنجاح.',
        ], 200);
    }

    /**
     * Revoke a permission from a role.
     *
     * Removes the specified permission from the specified role.
     *
     * @param int $roleId
     * @param int $permissionId
     * @return JsonResponse
     *
     * @hint Route:
     *        DELETE /api/roles/{roleId}/permissions/{permissionId}
     */
    public function revokePermission(
        int $roleId,
        int $permissionId
    ): JsonResponse {
        $role = Role::where(
            'guard_name',
            self::GUARD_NAME
        )->findOrFail($roleId);

        $permission = Permission::where('id', $permissionId)
            ->where('guard_name', self::GUARD_NAME)
            ->firstOrFail();

        if (!$role->hasPermissionTo($permission)) {
            return response()->json([
                'message' => 'هذه الصلاحية غير مضافة إلى هذا الدور.',
            ], 422);
        }

        $role->revokePermissionTo($permission);

        return response()->json([
            'message' => 'تمت إزالة الصلاحية من الدور بنجاح.',
        ], 200);
    }
}