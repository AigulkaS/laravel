<?php

namespace App\Http\Controllers\Role;

use App\Http\Filters\RoleFilter;
use App\Http\Requests\Role\FilterRequest;
use App\Http\Resources\RoleResource;
use App\Models\Role;

class IndexController extends BaseController
{
    public function __invoke(FilterRequest $request)
    {

        $data = $request->validated();
        
        $filter = app()->make(RoleFilter::class, ['queryParams' => array_filter($data)]);
        
        $roles = Role::filter($filter)->get();
        
        return RoleResource::collection($roles);
    }
  
}
