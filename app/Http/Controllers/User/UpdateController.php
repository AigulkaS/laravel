<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\UserResource;

class UpdateController extends BaseController
{
    public function __invoke(UpdateRequest $request, User $user)
    {
        if(!auth('sanctum')->check()) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        } else {
            $userChecked = auth('sanctum')->user();
            if ($userChecked->id == $user->id || $userChecked->role->name == 'admin' ||
                 (strpos($userChecked->role->name, 'moderator') !== false && $userChecked->hospital_id == $user->hospital_id)) {
                $data = $request->validated();
                $user = $this->service->update($user, $data);
                return new UserResource($user);
            } else {
                return response()->json([
                    'message' => 'Access is denied',
                ], 403);
            }
            
        }
        
    }


}
