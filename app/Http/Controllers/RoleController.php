<?php

namespace App\Http\Controllers;

use App\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        foreach($roles as $role) {
            dump($role->name);
        }
        $role = Role::where('id',1)->first();
        dump($role->name);
    }
}
