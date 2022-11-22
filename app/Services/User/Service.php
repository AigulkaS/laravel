<?php

namespace App\Services\User;

use App\Models\User;

class Service {

    public function store($data) {
        $user = User::create($data);
    }

    public function update($user, $data) {
        $user->update($data);
    }
}