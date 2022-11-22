<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Booking;
use App\Models\Brigade;
use App\Models\Disease;
use App\Models\Hospital;
use App\Models\HospitalRoom;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Today;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Role::factory(4)->create();
        Permission::factory(3)->create();
        for ($i = 1; $i <= 5; $i++) {
            $this->permissionRoleFactoryCreate();
        }
        Hospital::factory(5)->create();
        HospitalRoom::factory(5)->create();
        Disease::factory(2)->create();
        Brigade::factory(5)->create();
        User::factory(10)->create();
        Today::factory(1)->create();
        Booking::factory(5)->create();
    }

    private function permissionRoleFactoryCreate() {
        return DB::table('permission_role')->insert([
            'permission_id' => Permission::get()->random()->id,
            'Role_id' => Role::get()->random()->id,
        ]);
    }
}
