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
Route::group(['middleware' => ['auth', 'role:super-admin|secretary']], function () {
    Route::post('admin/cell/{id}/attach_member', 'App\Http\Controllers\CellController@attach_member');
    Route::post('admin/cell/{id}/detach_member', 'App\Http\Controllers\CellController@detach_member');
    Route::get('admin/cell/{id}/get_member_list', 'App\Http\Controllers\CellController@get_member_list');
    Route::resource('admin/cell', 'App\Http\Controllers\CellController');

    Route::resource('admin/region', 'App\Http\Controllers\RegionController');

    Route::get('admin/user/get_user_list', 'App\Http\Controllers\UserController@get_user_list');
    Route::get('admin/user/search_users', 'App\Http\Controllers\UserController@search_users');

    Route::resource('admin/training', 'App\Http\Controllers\TrainingController');
//    Route::get('admin/user', 'App\Http\Controllers\UserController@index');
});


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
