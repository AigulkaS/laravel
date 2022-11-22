<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Hospital;
use App\Models\User;

class EditController extends BaseController
{
    public function __invoke(User $user)
    {
        $roles = Role::all();
        $hospitals = Hospital::all();
        
        return view('user.edit', compact('user', 'roles', 'hospitals'));
    }
}
