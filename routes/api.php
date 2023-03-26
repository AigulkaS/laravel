<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register'])->name('register');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::post('/verify-email/{id}/{hash}', [\App\Http\Controllers\VerificationController::class, 'verify'])->name('verify');
Route::post('/verify-resend', [\App\Http\Controllers\VerificationController::class, 'resend'])->name('resend');
Route::post('/forgot-password', [\App\Http\Controllers\ForgotPasswordController::class, 'forgot_password']);
Route::post('/reset-password', \App\Http\Controllers\ResetPasswordController::class);



Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/get', [\App\Http\Controllers\GetController::class, 'index'])->name('get.index');
    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

});

// for users
Route::get('/users/create', App\Http\Controllers\User\CreateController::class)->name('user.create')->middleware('permissions:edit user');
Route::get('/users/{user}', App\Http\Controllers\User\ShowController::class,)->name('user.show');
Route::get('/users/{user}/edit', App\Http\Controllers\User\EditController::class,)->name('user.edit');
Route::patch('/users/{user}', App\Http\Controllers\User\UpdateController::class,)->name('user.update');

Route::get('/users', App\Http\Controllers\User\IndexController::class)->name('user.index')->middleware('permissions:edit user');
Route::post('/users', App\Http\Controllers\User\StoreController::class)->name('user.store')->middleware('permissions:edit user');
Route::delete('/users/{user}', App\Http\Controllers\User\DestroyController::class,)->name('user.delete')->middleware('permissions:edit user');
//

//for hospitals
Route::get('/hospitals/{hospital}', App\Http\Controllers\Hospital\ShowController::class,)->name('hospital.show')->middleware('permissions:edit hospital');
Route::get('/hospitals/{hospital}/edit', App\Http\Controllers\Hospital\EditController::class,)->name('hospital.edit')->middleware('permissions:edit hospital');
Route::patch('/hospitals/{hospital}', App\Http\Controllers\Hospital\UpdateController::class,)->name('hospital.update')->middleware('permissions:edit hospital');
// for hospital rooms
Route::delete('/hospital_rooms/{hospital_room}', App\Http\Controllers\Hospital\DestroyRoomController::class,)->name('hospital_room.delete')->middleware('permissions:edit hospital');
Route::get('/hospital_rooms/disable', App\Http\Controllers\Hospital\DisableController::class,)->name('hospital_room.disable')->middleware('permissions:disable hospital room');

//

Route::group(['middleware' => 'role:admin'], function () {

    // for roles
    Route::get('/roles', App\Http\Controllers\Role\IndexController::class)->name('role.index');
    Route::get('/roles/create', App\Http\Controllers\Role\CreateController::class)->name('role.create');
    Route::post('/roles', App\Http\Controllers\Role\StoreController::class)->name('role.store');
    Route::get('/roles/{role}', App\Http\Controllers\Role\ShowController::class,)->name('role.show');
    Route::get('/roles/{role}/edit', App\Http\Controllers\Role\EditController::class,)->name('role.edit');
    Route::patch('/roles/{role}', App\Http\Controllers\Role\UpdateController::class,)->name('role.update');
    Route::delete('/roles/{role}', App\Http\Controllers\Role\DestroyController::class,)->name('role.delete');
    //

    // for permissions
    Route::get('/permissions', App\Http\Controllers\Permission\IndexController::class)->name('permission.index');
    Route::get('/permissions/create', App\Http\Controllers\Permission\CreateController::class)->name('permission.create');
    Route::post('/permissions', App\Http\Controllers\Permission\StoreController::class)->name('permission.store');
    Route::get('/permissions/{permission}', App\Http\Controllers\Permission\ShowController::class,)->name('permission.show');
    Route::get('/permissions/{permission}/edit', App\Http\Controllers\Permission\EditController::class,)->name('permission.edit');
    Route::patch('/permissions/{permission}', App\Http\Controllers\Permission\UpdateController::class,)->name('permission.update');
    Route::delete('/permissions/{permission}', App\Http\Controllers\Permission\DestroyController::class,)->name('permission.delete');
    //

    // for hospitals
    Route::get('/hospitals', App\Http\Controllers\Hospital\IndexController::class)->name('hospital.index');
    Route::get('/hospitals/create', App\Http\Controllers\Hospital\CreateController::class)->name('hospital.create');
    Route::post('/hospitals', App\Http\Controllers\Hospital\StoreController::class)->name('hospital.store');
    Route::delete('/hospitals/{hospital}', App\Http\Controllers\Hospital\DestroyController::class,)->name('hospital.delete');
    //

    // for diseases
    Route::get('/diseases', App\Http\Controllers\Disease\IndexController::class)->name('disease.index');
    Route::get('/diseases/create', App\Http\Controllers\Disease\CreateController::class)->name('disease.create');
    Route::post('/diseases', App\Http\Controllers\Disease\StoreController::class)->name('disease.store');
    Route::get('/diseases/{disease}', App\Http\Controllers\Disease\ShowController::class,)->name('disease.show');
    Route::get('/diseases/{disease}/edit', App\Http\Controllers\Disease\EditController::class,)->name('disease.edit');
    Route::patch('/diseases/{disease}', App\Http\Controllers\Disease\UpdateController::class,)->name('disease.update');
    Route::delete('/diseases/{disease}', App\Http\Controllers\Disease\DestroyController::class,)->name('disease.delete');
    //
});

// for operators
Route::get('/operators', App\Http\Controllers\Operator\IndexController::class)->name('operator.index');
Route::get('/operators/edit', App\Http\Controllers\Operator\EditController::class,)->name('operator.edit')->middleware('permissions:edit operator');
Route::post('/operators', App\Http\Controllers\Operator\UpdateController::class,)->name('operator.update')->middleware('permissions:edit operator');
//

// for booking
Route::get('/bookings', App\Http\Controllers\Booking\IndexController::class)->name('booking.index');
Route::get('/bookings/create/disease', [\App\Http\Controllers\Booking\CreateController::class, 'disease'])->name('disease')->middleware('permissions:create booking');
// Route::get('/bookings/create/hospital', [\App\Http\Controllers\Booking\CreateController::class, 'hospital'])->name('hospital')->middleware('permissions:show booking,create booking,edit booking');
// Route::post('/bookings', App\Http\Controllers\Booking\StoreController::class)->name('booking.store')->middleware('permissions:show booking,create booking,edit booking');
Route::post('/bookings', App\Http\Controllers\Booking\StoreControllerNew::class)->name('booking.store')->middleware('permissions:create booking');
Route::patch('/bookings', App\Http\Controllers\Booking\UpdateController::class,)->name('booking.update')->middleware('permissions:update booking');
//

Route::post('/push', [\App\Http\Controllers\PushController::class, 'store'])->name('store');
Route::post('/notifications', [\App\Http\Controllers\PushController::class, 'storePush'])->name('storePush');
Route::get('/push', [\App\Http\Controllers\PushController::class, 'push'])->name('push');

// "geo_lat": "54.728914",
//     "geo_lon": "55.967459",
//     "disease_id": 1,
//     "condition_id": 2

// "type": 1,
//     "full_name": "djjsd",
//     "short_name": "smnd",
//     "address": "dskjn",
//     "hospital_rooms": [],
//     "geo_lat": "54.728914",
//     "geo_lon": "55.967459"