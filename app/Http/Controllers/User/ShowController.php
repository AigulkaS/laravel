<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;

class ShowController extends BaseController
{
    public function __invoke(User $user)
    {
        if(!auth('sanctum')->check()) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        } else {
            $userChecked = auth('sanctum')->user();
            if ($userChecked->id == $user->id || $userChecked->role->name == 'admin' ||
                 (strpos($userChecked->role->name, 'moderator') !== false && $userChecked->hospital_id == $user->hospital_id)) {
                    return new UserResource($user);
            } else {
                return response()->json([
                    'message' => 'Access is denied',
                ], 403);
            }
            
        }
    }

                
            
}
