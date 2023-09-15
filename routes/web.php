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


    Route::resource('admin/training_category', 'App\Http\Controllers\TrainingCategoryController');

    Route::post('admin/membergroup/{id}/attach_member', 'App\Http\Controllers\MemberGroupController@attach_member');
    Route::post('admin/membergroup/{id}/detach_member', 'App\Http\Controllers\MemberGroupController@detach_member');
    Route::get('admin/membergroup/{id}/get_member_list', 'App\Http\Controllers\MemberGroupController@get_member_list');
    Route::resource('admin/membergroup', 'App\Http\Controllers\MemberGroupController');
//    Route::get('admin/user', 'App\Http\Controllers\UserController@index');
});


Route::group(['middleware' => ['auth', 'role:super-admin']], function () {
    Route::any('admin/user/batch', 'App\Http\Controllers\UserController@batch');
    Route::resource('admin/user', 'App\Http\Controllers\UserController');

    Route::get('admin/setting/change_setting', 'App\Http\Controllers\SettingController@change_setting');
    Route::resource('admin/setting', 'App\Http\Controllers\SettingController');
//    Route::get('admin/user', 'App\Http\Controllers\UserController@index');
});


Route::group(['middleware' => ['auth'], 'prefix' => '/web'], function () {
    Route::get('/training/upcoming_trainings', 'App\Http\Controllers\TrainingController@upcoming_trainings');
    Route::get('/training/{id}/get_zoom_join_link', 'App\Http\Controllers\TrainingController@get_zoom_join_link');
    Route::get('/training/{id}/show_video', 'App\Http\Controllers\TrainingController@show_video');
    Route::post('/training/{id}/update_watch_point', 'App\Http\Controllers\TrainingController@update_watch_point');
    Route::post('/training/{id}/finish_lection', 'App\Http\Controllers\TrainingController@finish_lection');


    Route::post('/user/update_timezone', 'App\Http\Controllers\UserController@update_timezone');
});


Route::group(['middleware' => ['auth']], function () {
    Route::get('/', 'App\Http\Controllers\HomeController@main_page');
});

Route::get('redirect_zoom', 'App\Http\Controllers\ZoomController@redirect_zoom');
Route::any('zoom_webhook', 'App\Http\Controllers\ZoomController@zoom_webhook');


Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
