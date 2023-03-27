<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;

class UserService {

    public function store($data) {
        $role = Role::find($data['role_id']);
        if($role->name == 'surgeon' || $role->name == 'cardiologist') {
            $data['push'] = 1;
        }
        $user = User::create($data);
        return $user;
    }

    public function update($user, $data) {
        $user->update($data);
        return $user->fresh();
    }
}