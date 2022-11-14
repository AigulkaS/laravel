<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});
////
//Auth::routes();
//
//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/roles', [App\Http\Controllers\RoleController::class, 'index'])->name('roles');
Route::get('/users/create', [App\Http\Controllers\UserController::class, 'create'])->name('users/create');
Route::get('/users/delete', [App\Http\Controllers\UserController::class, 'delete'])->name('users/delete');
Route::get('/users/updateOrCreate', [App\Http\Controllers\UserController::class, 'updateOrCreate'])->name('users/updateOrCreate');

//Route::get('{any}', function () {
//    return view('welcome');
//})->where('any', '.*');

