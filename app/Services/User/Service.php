<?php

namespace App\Services\User;

use App\Models\User;

class Service {

    public function store($data) {
        $user = User::create($data);

        return $user;
    }

    public function update($user, $data) {
        $user->update($data);
        return $user->fresh();
    }
}