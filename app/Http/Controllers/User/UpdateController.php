<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\UserResource;

class UpdateController extends BaseController
{
    public function __invoke(UpdateRequest $request, User $user)
    {
        $data = $request->validated();

        $user = $this->service->update($user, $data);
            
        return new UserResource($user);
        // return redirect()->route('user.show', $user->id);
    }

        
}
