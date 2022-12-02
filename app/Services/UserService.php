<?php

namespace App\Services;

use App\Models\User;

class UserService {

    public function store($data) {
        $user = User::create($data);

        return $user;
    }

    public function update($user, $data) {
        $user->update($data);
        return $user->fresh();
    }
}