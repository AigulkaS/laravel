<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\HospitalResource;
use App\Http\Resources\RoleResource;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\Hospital;
use App\Models\User;

class EditController extends BaseController
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
                $roles = null;
                $hospitals = null;
                $userCheck = auth('sanctum')->user();
                switch ($userCheck->role->name) {
                    case 'admin':
                        $roles = Role::all();
                        $hospitals = Hospital::where('type',1)->get();
                        $smps = Hospital::where('type',2)->get();
                        break;
                    case 'moderator hosp':
                        $roles = Role::where('name', 'surgeon')->orWhere('name', 'cardiologist')
                            ->orWhere('name', 'moderator hosp')->get();
                        $hospitals = Hospital::where('id', $user->hospital->id)->get();
                        break;
                    case 'moderator smp':
                        $roles = Role::where('name', 'smp')->orWhere('name', 'moderator smp')->get();
                        $smps = Hospital::where('id', $user->hospital->id)->get();
                        break;
                }
                return response()->json([
                    'roles' => RoleResource::collection($roles),
                    'hospitals' => $hospitals == null ? [] : HospitalResource::collection($hospitals),
                    'smps' => $smps == null ? [] : HospitalResource::collection($smps),
                    'user' => new UserResource($user),
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Access is denied',
                ], 403);
            }
            
        }
    }
}
