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

Route::group(['middleware' => ['auth', 'role:super-admin']], function () {
    Route::any('admin/user/batch', 'App\Http\Controllers\UserController@batch');
    Route::resource('admin/user', 'App\Http\Controllers\UserController');
//    Route::get('admin/user', 'App\Http\Controllers\UserController@index');
});


Route::group(['middleware' => ['auth']], function () {
    Route::get('/', 'App\Http\Controllers\HomeController@main_page');
});


Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
