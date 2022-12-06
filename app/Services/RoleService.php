<?php

namespace App\Services;

use App\Models\Role;

class RoleService {

    public function store($data) {
        $role = Role::create($data);

        return $role;
    }

    public function update($role, $data) {
        $role->update($data);
        return $role->fresh();
    }
}