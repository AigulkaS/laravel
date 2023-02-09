<?php

namespace App\Http\Controllers\Role;

use App\Http\Requests\Role\StoreRequest;
use App\Http\Resources\RoleResource;
use App\Models\Permission;
use App\Models\Role;

class StoreController extends BaseController
{

    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();

        $role = $this->service->store($data);

        return $role instanceof Role ? new RoleResource($role) : $role;

        // if ($role instanceof Role) {
        //     return response()->json([
        //         'role' => new RoleResource($role),
        //         'permissions' =>Permission::collection($role->permissions)
        //     ], 200);
        // } else {
        //     return $role;
        // }
    }


                
            
}
