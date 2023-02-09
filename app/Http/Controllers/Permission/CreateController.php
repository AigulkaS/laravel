<?php

namespace App\Http\Controllers\Permission;

use App\Http\Resources\RoleResource;
use App\Models\Role;

class CreateController extends BaseController
{
    public function __invoke()
    {
        $roles = Role::all();
        
        return response()->json([
            'roles' => RoleResource::collection($roles),
        ], 200);

    }




                
            
}
