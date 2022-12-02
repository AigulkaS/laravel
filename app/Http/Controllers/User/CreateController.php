<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\HospitalResource;
use App\Http\Resources\RoleResource;
use App\Models\Hospital;
use App\Models\Role;
use App\Models\User;

class CreateController extends BaseController
{
    public function __invoke()
    {
        $roles = Role::all();
        $hospitals = Hospital::all();
        
        return response()->json([
            'roles' => RoleResource::collection($roles),
            'hospitals' => HospitalResource::collection($hospitals),
        ], 200);

        // return view('user.create', compact('roles', 'hospitals'));
    }




                
            
}
