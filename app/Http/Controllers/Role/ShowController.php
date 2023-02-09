<?php

namespace App\Http\Controllers\Role;

use App\Http\Resources\PermissionResource;
use App\Http\Resources\RoleResource;
use App\Models\Role;


class ShowController extends BaseController
{
    public function __invoke(Role $role)
    {
        return response()->json([
            'role' => new RoleResource($role),
            'permissions' =>PermissionResource::collection($role->permissions)
        ], 200);
    }

                
            
}
