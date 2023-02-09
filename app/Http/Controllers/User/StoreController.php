<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\User\StoreRequest;
use App\Http\Resources\UserResource;

class StoreController extends BaseController
{

    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();

        $user = $this->service->store($data);

        return new UserResource($user);

        // return redirect()->route('user.index');
    }


                
            
}
