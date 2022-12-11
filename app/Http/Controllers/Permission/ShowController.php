<?php

namespace App\Http\Controllers\Permission;

use App\Http\Resources\PermissionResource;
use App\Http\Resources\RoleResource;
use App\Models\Permission;

class ShowController extends BaseController
{
    public function __invoke(Permission $permission)
    {
        return response()->json([
            'permission' => new PermissionResource($permission),
            'roles' =>RoleResource::collection($permission->roles)
        ], 200);
    }

                
            
}
