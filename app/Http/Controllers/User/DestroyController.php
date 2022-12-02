<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;

class DestroyController extends BaseController
{
    public function __invoke(User $user)
    {
        try {
            $user->delete();
            return response()->json([
                "msg" => "delete successfully",
            ], 200);
        } catch(\Exception $exception) {
            return response()->json([
                "msg" => $exception->getMessage(),
            ], 419);
        }
    }


}
