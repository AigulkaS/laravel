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

Route::get('/users', App\Http\Controllers\User\IndexController::class)->name('user.index');
Route::get('/users/create', App\Http\Controllers\User\CreateController::class)->name('user.create');

  Route::post('/users', App\Http\Controllers\User\StoreController::class)->name('user.store');
  Route::get('/users/{user}', App\Http\Controllers\User\ShowController::class,)->name('user.show');
  Route::get('/users/{user}/edit', App\Http\Controllers\User\EditController::class,)->name('user.edit');
  Route::patch('/users/{user}', App\Http\Controllers\User\UpdateController::class,)->name('user.update');
  Route::delete('/users/{user}', App\Http\Controllers\User\DestroyController::class,)->name('user.delete');


