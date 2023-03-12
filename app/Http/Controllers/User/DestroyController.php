<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;

class DestroyController extends BaseController
{
    public function __invoke(User $user)
    {
        if(!auth('sanctum')->check()) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        } else {
            $userChecked = auth('sanctum')->user();
            if ($userChecked->role->name == 'admin' ||
                 (strpos($userChecked->role->name, 'moderator') !== false && $userChecked->hospital_id == $user->hospital_id)) {
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
            } else {
                return response()->json([
                    'message' => 'Access is denied',
                ], 403);
            }
            
        }
    }


}
