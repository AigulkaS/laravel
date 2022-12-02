<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
use App\Http\Resources\User\UserResource;

class StoreController extends BaseController
{

    public function __invoke(StoreRequest $request)
    // public function __invoke()
    {
        $data = $request->validated();

        $user = $this->service->store($data);

        return new UserResource($user);

        

        // return redirect()->route('user.index');
    }


                
            
}
