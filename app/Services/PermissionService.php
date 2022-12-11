<?php

namespace App\Services;

use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionService {

    public function store($data) {
        try {
            DB::beginTransaction();

            $roles = $data['roles'];

            unset($data['roles']);

            $permission = Permission::create($data);

            $permission->roles()->attach($roles);

            DB::commit();
        } catch(\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        } 
        return $permission;
    }

    public function update($permission, $data) {

        try {
            DB::beginTransaction();

            $roles = $data['roles'];

            unset($data['roles']);

            $permission->update($data);

            $permission->roles()->sync($roles);

            DB::commit();
        } catch(\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        } 
        return $permission->fresh();
    }

    public function delete($permission) {
        try {
            DB::beginTransaction();

            $permission->roles()->detach();
            $permission->delete();

            DB::commit();
        } catch(\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        } 
        return 'delete successfully';
    }
}