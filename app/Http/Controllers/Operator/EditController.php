<?php

namespace App\Http\Controllers\Operator;

use App\Http\Filters\RoleFilter;
use App\Http\Filters\UserFilter;
use App\Http\Requests\Operator\EditRequest;
// use App\Http\Requests\Today\EditRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;

class EditController extends BaseController
{
    public function __invoke(EditRequest $request)
    {
        $data = $request->validated();

        $roleData = [
            'name' => 'surgeon',
        ];
        $filter = app()->make(RoleFilter::class, ['queryParams' => array_filter($roleData)]);
        $surgeonRoles = Role::filter($filter)->get();
        $data['role_id'] = $surgeonRoles[0]->id; 

        $filter = app()->make(UserFilter::class, ['queryParams' => array_filter($data)]);
        $surgeons = User::filter($filter)->get();


        $roleData = [
            'name' => 'cardiologist',
        ];
        $filter = app()->make(RoleFilter::class, ['queryParams' => array_filter($roleData)]);
        $cardiologistRoles = Role::filter($filter)->get();
        $data['role_id'] = $cardiologistRoles[0]->id; 

        $filter = app()->make(UserFilter::class, ['queryParams' => array_filter($data)]);
        $cardiologist = User::filter($filter)->get();


        return response()->json([
            'surgeons' => UserResource::collection($surgeons),
            'cardiologist' => UserResource::collection($cardiologist),
        ], 200);
    
    }
}
