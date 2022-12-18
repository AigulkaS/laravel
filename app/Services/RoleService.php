<?php

namespace App\Services;

use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleService {

    public function store($data) {

        try {
            DB::beginTransaction();
            $permissions = $data['permissions'];

            unset($data['permissions']);

            $role = Role::create($data);

            $role->permissions()->attach($permissions);
            DB::commit();
        } catch(\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        } 

        return $role;
    }

    public function update($role, $data) {
        if ($role->name === 'admin' 
        || $role->name === 'surgeon' 
        || $role->name === 'cardiologist') {
            return 'not access to update';
        } else {
            try {
                DB::beginTransaction();
                $permissions = $data['permissions'];
    
                unset($data['permissions']);
    
                $role->update($data);
    
                $role->permissions()->sync($permissions);
                DB::commit();
            } catch(\Exception $e) {
                DB::rollBack();
                return $e->getMessage();
            } 
    
            return $role->fresh();
        }
    }

    public function delete($role) {
        if ($role->name === 'admin' 
        || $role->name === 'surgeon' 
        || $role->name === 'cardiologist') {
            return 'not access to delete';
        } else {
            try {
                DB::beginTransaction();
    
                $role->permissions()->detach();
                $role->delete();
    
                DB::commit();
            } catch(\Exception $e) {
                DB::rollBack();
                return $e->getMessage();
            } 
            return 'delete successfully';
        }
        
    }
}