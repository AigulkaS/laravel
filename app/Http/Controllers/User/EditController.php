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
        $roles = Role::all();
        $hospitals = Hospital::all();

        return response()->json([
            'user' => new UserResource($user),
            'roles' => RoleResource::collection($roles),
            'hospitals' => HospitalResource::collection($hospitals),
        ], 200);
        
        // return view('user.edit', compact('user', 'roles', 'hospitals'));
    }
}
