<?php

namespace App\Http\Controllers\Permission;

use App\Http\Requests\Permission\StoreRequest;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\RoleResource;
use App\Models\Permission;

class StoreController extends BaseController
{

    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();

        $permission = $this->service->store($data);

        return $permission instanceof Permission ? new PermissionResource($permission) : $permission;

        // if ($permission instanceof Permission) {
        //     return response()->json([
        //         'permission' => new PermissionResource($permission),
        //         'roles' =>RoleResource::collection($permission->roles)
        //     ], 200);
        // } else {
        //     return $permission;
        // }
        

    }


                
            
}
