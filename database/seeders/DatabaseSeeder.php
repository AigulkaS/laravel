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
        DB::table('roles')->insert([
            'name' => 'admin',
            'label' => 'админ',
        ]);
        DB::table('roles')->insert([
            'name' => 'surgeon',
            'label' => 'хирург',
        ]);
        DB::table('roles')->insert([
            'name' => 'cardiologist',
            'label' => 'кардиолог',
        ]);
        DB::table('roles')->insert([
            'name' => 'dispatcher',
            'label' => 'дистпетчер',
        ]);
        
        DB::table('permissions')->insert([
            'name' => 'create booking',
            'label' => 'create booking',
        ]);

        DB::table('permissions')->insert([
            'name' => 'edit booking',
            'label' => 'edit booking',
        ]);

        DB::table('permissions')->insert([
            'name' => 'show booking',
            'label' => 'show booking',
        ]);

        DB::table('permissions')->insert([
            'name' => 'edit today',
            'label' => 'edit today',
        ]);

        DB::table('permissions')->insert([
            'name' => 'show today',
            'label' => 'show today',
        ]);

        for ($i = 1; $i <= 5; $i++) {
            DB::table('permission_roles')->insert([
                'permission_id' => $i,
                'role_id' => 1,
            ]);
            DB::table('permission_roles')->insert([
                'permission_id' => $i,
                'role_id' => 2,
            ]);
            DB::table('permission_roles')->insert([
                'permission_id' => $i,
                'role_id' => 3,
            ]);

            if ($i != 4) {
                DB::table('permission_roles')->insert([
                    'permission_id' => $i,
                    'role_id' => 4,
                ]);
            }  
        }

        DB::table('hospitals')->insert([
            'full_name' => 'default',
            'short_name' => 'default',
            'address' => 'default',
            'geo_lat' => '0.0',
            'geo_lon' => '0.0',
        ]);

        DB::table('hospital_rooms')->insert([
            'hospital_id' => 1,
            'name' => 'default',
        ]);

//         HospitalRoom::factory(4)->create();
        DB::table('diseases')->insert([
            'name' => 'OKC ST+',
            'code' => 1,
        ]);
        DB::table('diseases')->insert([
            'name' => 'OKC ST-',
            'code' => 2,
        ]);
// ;
//         User::factory(10)->create();

        $this->todaysFactoryCreate();
//         Booking::factory(10)->create();
    }

    private function permissionRoleFactoryCreate() {
        return DB::table('permission_roles')->insert([
            'permission_id' => Permission::get()->random()->id,
            'role_id' => Role::get()->random()->id,
        ]);
    }

    private function todaysFactoryCreate() {
        // $surgeons = User::where('role_id', 2)->get();
        // $cardiologists = User::where('role_id', 3)->get();
        $hospitals=Hospital::all();
        foreach($hospitals as $hospital) {
            DB::table('todays')->insert([
                'hospital_id' => $hospital->id,
                // 'surgeon_id' => $surgeons->random()->id,
                // 'cardiologist_id' => $cardiologists->random()->id,
            ]);
        }
    }
}
