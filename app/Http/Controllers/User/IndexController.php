<?php

namespace App\Http\Controllers\User;

use App\Http\Filters\UserFilter;
use App\Models\User;
use App\Http\Requests\User\FilterRequest;
use App\Http\Resources\UserResource;

class IndexController extends BaseController
{
    public function __invoke(FilterRequest $request)
    {

        $data = $request->validated();
        
        $filter = app()->make(UserFilter::class, ['queryParams' => array_filter($data)]);
        
        $users = User::filter($filter)->get();
        // $users = User::all();
        // dd($users);
        
        return UserResource::collection($users);
        // return view('user.index', compact('users'));
    }
  
}
