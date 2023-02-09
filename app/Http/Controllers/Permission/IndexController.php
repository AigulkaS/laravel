<?php

namespace App\Http\Controllers\Permission;

use App\Http\Filters\PermissionFilter;
use App\Http\Requests\Permission\FilterRequest;
use App\Http\Resources\PermissionResource;
use App\Models\Permission;

class IndexController extends BaseController
{
    public function __invoke(FilterRequest $request)
    {

        $data = $request->validated();
        
        $filter = app()->make(PermissionFilter::class, ['queryParams' => array_filter($data)]);
        
        $roles = Permission::filter($filter)->get();
        
        return PermissionResource::collection($roles);

        // return response()->json([
        //             'permission' => new PermissionResource($permission),
        //             'roles' =>RoleResource::collection($permission->roles)
        //         ], 200);
    }
  
}
