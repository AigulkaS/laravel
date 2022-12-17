<?php

namespace App\Http\Controllers\Today;

use App\Http\Filters\RoleFilter;
use App\Http\Filters\UserFilter;
use App\Http\Requests\Today\EditRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;

class EditController extends BaseController
{
    public function __invoke(EditRequest $request)
    {
        $data = $request->validated();

        // $roleData = [
        //     'name' => 'surgeon',
        // ];
        // $filter = app()->make(RoleFilter::class, ['queryParams' => array_filter($roleDat)]);
        // $surgeonRoles = Role::filter($filter)->get();
        // $data['role_id'] = $surgeonRoles[0]->id; 

        //! role_id = 1 it's surgeon
        $data['role_id'] = 1; 
        $filter = app()->make(UserFilter::class, ['queryParams' => array_filter($data)]);
        $surgeons = User::filter($filter)->get();


        // $roleData = [
        //     'name' => 'cardiologist',
        // ];
        // $filter = app()->make(RoleFilter::class, ['queryParams' => array_filter($roleDat)]);
        // $cardiologistRoles = Role::filter($filter)->get();
        // $data['role_id'] = $cardiologistRoles[0]->id; 

        //! role_id = 2 it's cardiologist
        $data['role_id'] = 2; 
        $filter = app()->make(UserFilter::class, ['queryParams' => array_filter($data)]);
        $cardiologist = User::filter($filter)->get();


        return response()->json([
            'surgeons' => UserResource::collection($surgeons),
            'cardiologist' => UserResource::collection($cardiologist),
        ], 200);
    
    }
}
