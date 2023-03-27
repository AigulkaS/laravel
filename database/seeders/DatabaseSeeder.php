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
            'name' => 'smp',
            'label' => 'фельдшер/врач СМП',
        ]);
        DB::table('roles')->insert([
            'name' => 'moderator hosp',
            'label' => 'модератор больницы',
        ]);
        DB::table('roles')->insert([
            'name' => 'moderator smp',
            'label' => 'модератор смп',
        ]);
        

        DB::table('permissions')->insert([
            'name' => 'edit user',
            'label' => 'редактировать пользователей',
        ]);

        DB::table('permissions')->insert([
            'name' => 'edit hospital',
            'label' => 'редактировать больницу',
        ]);

        DB::table('permissions')->insert([
            'name' => 'disable hospital room',
            'label' => 'приостановить операционную',
        ]);

        DB::table('permissions')->insert([
            'name' => 'edit operator',
            'label' => 'редактировать дежурных',
        ]);

        DB::table('permissions')->insert([
            'name' => 'create booking',
            'label' => 'создать бронь',
        ]);

        DB::table('permissions')->insert([
            'name' => 'update booking',
            'label' => 'редактировать бронь',
        ]);


            DB::table('permission_roles')->insert([
                'permission_id' => 1,
                'role_id' => 1,
            ]);
            DB::table('permission_roles')->insert([
                'permission_id' => 1,
                'role_id' => 5,
            ]);
            DB::table('permission_roles')->insert([
                'permission_id' => 1,
                'role_id' => 6,
            ]);

            DB::table('permission_roles')->insert([
                'permission_id' => 2,
                'role_id' => 1,
            ]);
            DB::table('permission_roles')->insert([
                'permission_id' => 2,
                'role_id' => 5,
            ]);
            DB::table('permission_roles')->insert([
                'permission_id' => 2,
                'role_id' => 6,
            ]);
            
            DB::table('permission_roles')->insert([
                'permission_id' => 3,
                'role_id' => 1,
            ]);
            DB::table('permission_roles')->insert([
                'permission_id' => 3,
                'role_id' => 2,
            ]);
            DB::table('permission_roles')->insert([
                'permission_id' => 3,
                'role_id' => 5,
            ]);
            DB::table('permission_roles')->insert([
                'permission_id' => 3,
                'role_id' => 6,
            ]);
            DB::table('permission_roles')->insert([
                'permission_id' => 4,
                'role_id' => 1,
            ]);
            DB::table('permission_roles')->insert([
                'permission_id' => 4,
                'role_id' => 2,
            ]);
            DB::table('permission_roles')->insert([
                'permission_id' => 4,
                'role_id' => 3,
            ]);
            DB::table('permission_roles')->insert([
                'permission_id' => 4,
                'role_id' => 5,
            ]);
            DB::table('permission_roles')->insert([
                'permission_id' => 5,
                'role_id' => 1,
            ]);
            DB::table('permission_roles')->insert([
                'permission_id' => 5,
                'role_id' => 4,
            ]);
            DB::table('permission_roles')->insert([
                'permission_id' => 5,
                'role_id' => 6,
            ]);
            DB::table('permission_roles')->insert([
                'permission_id' => 6,
                'role_id' => 1,
            ]);
            DB::table('permission_roles')->insert([
                'permission_id' => 6,
                'role_id' => 2,
            ]);
            DB::table('permission_roles')->insert([
                'permission_id' => 6,
                'role_id' => 3,
            ]);
            DB::table('permission_roles')->insert([
                'permission_id' => 6,
                'role_id' => 5,
            ]);
        

        DB::table('hospitals')->insert([
            'type' => 1,
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

        DB::table('patient_conditions')->insert([
            'name' => 'Среднее',
        ]);
        DB::table('patient_conditions')->insert([
            'name' => 'Тяжелое',
        ]);
        DB::table('patient_conditions')->insert([
            'name' => 'Крайне тяжелое',
        ]);
// ;
        User::factory(10)->create();

        // $this->todaysFactoryCreate();
//         Booking::factory(10)->create();
    }

    private function permissionRoleFactoryCreate() {
        return DB::table('permission_roles')->insert([
            'permission_id' => Permission::get()->random()->id,
            'role_id' => Role::get()->random()->id,
        ]);
    }

    private function todaysFactoryCreate() {
        $surgeons = User::where('role_id', 2)->get();
        $cardiologists = User::where('role_id', 3)->get();
        $hospitals=Hospital::all();
        foreach($hospitals as $hospital) {
            DB::table('todays')->insert([
                'hospital_id' => $hospital->id,
                'surgeon_id' => $surgeons->random()->id,
                'cardiologist_id' => $cardiologists->random()->id,
            ]);
        }
    }
}
