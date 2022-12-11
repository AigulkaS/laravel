<?php

namespace App\Http\Controllers\Permission;

use App\Http\Resources\PermissionResource;
use App\Http\Resources\RoleResource;
use App\Models\Permission;
use App\Models\Role;

class EditController extends BaseController
{
    public function __invoke(Permission $permission)
    {
        $roles = Role::all();

        return response()->json([
            'permission' => new PermissionResource($permission),
            'permission_roles' => RoleResource::collection($permission->roles),
            'roles' => RoleResource::collection($roles),
        ], 200);
    
    }
}
