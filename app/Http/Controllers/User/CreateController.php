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
        $hospitals = Hospital::where('type',1)->get();
        $smps = Hospital::where('type',2)->get();

        return response()->json([
            'roles' => RoleResource::collection($roles),
            'hospitals' => HospitalResource::collection($hospitals),
            'smps' => HospitalResource::collection($smps),
        ], 200);

        // return view('user.create', compact('roles', 'hospitals'));
    }






}
