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
        
        // $users = User::filter($filter)->get();
        $users = User::filter($filter)->paginate(10);
        
        return UserResource::collection($users);
    }
  
}
