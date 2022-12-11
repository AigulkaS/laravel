<?php

namespace App\Http\Controllers\Permission;

use App\Http\Requests\Permission\UpdateRequest;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\RoleResource;
use App\Models\Permission;

class UpdateController extends BaseController
{
    public function __invoke(UpdateRequest $request, Permission $permission)
    {
        $data = $request->validated();

        $permission = $this->service->update($permission, $data);

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
