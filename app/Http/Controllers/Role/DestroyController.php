<?php

namespace App\Http\Controllers\Role;

use App\Models\Role;

class DestroyController extends BaseController
{
    public function __invoke(Role $role)
    {
        $message = $this->service->delete($role);

        return $message;
        
        // try {
        //     $role->delete();
        //     return response()->json([
        //         "msg" => "delete successfully",
        //     ], 200);
        // } catch(\Exception $exception) {
        //     return response()->json([
        //         "msg" => $exception->getMessage(),
        //     ], 419);
        // }
    }


}
