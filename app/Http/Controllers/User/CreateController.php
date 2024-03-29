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
        $roles = null;
        $hospitals = null;
        $smps = null;
        $user = auth('sanctum')->user();
        switch ($user->role->name) {
            case 'admin':
                $roles = Role::all();
                // $roles = Role::whereNot('name', 'admin')->get();
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

        // $hospitals = Hospital::where('type',1)->get();
        // $smps = Hospital::where('type',2)->get();

        return response()->json([
            'roles' => RoleResource::collection($roles),
            'hospitals' => $hospitals == null ? [] : HospitalResource::collection($hospitals),
            'smps' => $smps == null ? [] : HospitalResource::collection($smps),
        ], 200);

        // return view('user.create', compact('roles', 'hospitals'));
    }






}
