<?php

namespace App\Http\Controllers\Role;

use App\Http\Resources\RoleResource;
use App\Models\Role;

class EditController extends BaseController
{
    public function __invoke(Role $role)
    {
        return new RoleResource($role);
    }
}
