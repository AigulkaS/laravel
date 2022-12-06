<?php

namespace App\Http\Controllers\Role;

use App\Http\Requests\Role\UpdateRequest;
use App\Http\Resources\RoleResource;
use App\Models\Role;

class UpdateController extends BaseController
{
    public function __invoke(UpdateRequest $request, Role $role)
    {
        $data = $request->validated();

        $role = $this->service->update($role, $data);
            
        return new RoleResource($role);
    }

        
}
