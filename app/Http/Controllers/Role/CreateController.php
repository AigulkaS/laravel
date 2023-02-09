<?php

namespace App\Http\Controllers\Role;

use App\Http\Resources\PermissionResource;
use App\Models\Permission;

class CreateController extends BaseController
{
    public function __invoke()
    {
        $permissions = Permission::all();
        
        return response()->json([
            'permissions' => PermissionResource::collection($permissions),
        ], 200);
    }




                
            
}
