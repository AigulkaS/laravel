<?php

namespace App\Http\Controllers\Role;

use App\Http\Resources\PermissionResource;
use App\Http\Resources\RoleResource;
use App\Models\Permission;
use App\Models\Role;

class EditController extends BaseController
{
    public function __invoke(Role $role)
    {
        $permissions = Permission::all();

        return response()->json([
            'role' => new RoleResource($role),
            'role_permissions' => PermissionResource::collection($role->permissions),
            'permissions' => PermissionResource::collection($permissions),
        ], 200);
    }
}
