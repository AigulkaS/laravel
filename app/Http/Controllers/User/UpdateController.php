<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\User\UpdateRequest;

class UpdateController extends BaseController
{
    public function __invoke(UpdateRequest $request, User $user)
    {
        $data = $request->validated();

        $this->service->update($user, $data);
            
        return redirect()->route('user.show', $user->id);
    }

        
}
