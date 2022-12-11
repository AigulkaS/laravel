<?php

namespace App\Http\Controllers\Permission;

use App\Models\Permission;

class DestroyController extends BaseController
{
    public function __invoke(Permission $permission)
    {

        $message = $this->service->delete($permission);

        return $message;
    }


}
