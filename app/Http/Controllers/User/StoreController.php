<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
use App\Models\User;

class StoreController extends Controller
{

    public function create(StoreRequest $request)
    {
        $data = $request->validated();
        return $data;
    }

    // public function create()
    // {
    //     $usersArr = [
    //         [
    //             'role_id' => 4,
    //             'hospital_id' => 4,
    //             'last_name' => 'Smit',
    //             'first_name' => 'Lara',
    //             'patronymic' => 'Craft',
    //             'phone' => '9999999',
    //             'email' => 'Lara@gmail.com',
    //             'password' => '111111',
    //             'push' => 0,
    //             'sms' => 0,
    //         ],
    //         [
    //             'role_id' => 2,
    //             'hospital_id' => 1,
    //             'last_name' => 'Gas',
    //             'first_name' => 'Tom',
    //             'patronymic' => 'Word',
    //             'phone' => '8888',
    //             'email' => 'Tom@gmail.com',
    //             'password' => '222222',
    //             'push' => 0,
    //             'sms' => 0,
    //         ],
    //         [
    //             'role_id' => 3,
    //             'hospital_id' => 1,
    //             'last_name' => 'Yan',
    //             'first_name' => 'Den',
    //             'patronymic' => 'Puf',
    //             'phone' => '7777777',
    //             'email' => 'Den@gmail.com',
    //             'password' => '333333',
    //             'push' => 0,
    //             'sms' => 0,
    //         ],
            
    //     ];

    //     foreach($usersArr as $item) {
    //         User::create($item);
    //     }

    //     dd('created');
    // }

    // public function delete() {
    //     // don't run; soft deleted
    //     $user = User::find(1);
    //     $user->delete();
    //     dd('deleted');

    //     // for search and restore

    //     $deletedUser = User::withoutTrashed()->find(1);
    //     $deletedUser->restore();
    //     dd('deleted');
    // }

    // public function updateOrCreate() {
    //     $user = [
    //         'patronymic' => 'Dooom',
    //         'phone' => '555555',
    //         'push' => 1,
    //         'sms' => 1,
    //     ];

    //     User::updateOrCreate([
    //         'email' => 'Lara@gmail.com',
    //     ], $user);

    //     dump('updated');
    // }


                
            
}
