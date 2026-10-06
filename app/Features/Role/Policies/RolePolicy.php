<?php

namespace App\Features\Role\Policies;

use App\Features\Auth\Models\User;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    /**
     * Determine whether the user can view all roles.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('عرض الأدوار');
    }
    

    /**
     * Determine whether the user can create a role.
     */
    public function create(User $user): bool
    {
        return $user->can('إضافة دور');
    }

    /**
     * Determine whether the user can update a role.
     */
    public function update(
        User $user,
        Role $role
    ): bool {
        return $user->can('تعديل دور');
    }

    /**
     * Determine whether the user can delete a role.
     */
    public function delete(
        User $user,
        Role $role
    ): bool {
        return $user->can('حذف دور');
    }

    /**
     * Determine whether the user can view permissions
     * assigned to a specific role.
     */
    public function viewPermissions(
        User $user,
        Role $role
    ): bool {
        return $user->can('عرض صلاحيات الدور');
    }

    /**
     * Determine whether the user can give a permission to a role.
     */
    public function givePermission(
        User $user,
        Role $role
    ): bool {
        return $user->can('إضافة صلاحية للدور');
    }

    /**
     * Determine whether the user can revoke a permission from a role.
     */
    public function revokePermission(
        User $user,
        Role $role
    ): bool {
        return $user->can('إزالة صلاحية من الدور');
    }
}