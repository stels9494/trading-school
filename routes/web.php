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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes([
	'register' => false,
	'reset' => false,
	'confirm' => false,
	'verify' => false,
]);

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('/admin/commands', 'Admin\CommandController', ['as' => 'admin']);
Route::resource('/admin/commands/{command}/users', 'Admin\UserController', ['as' => 'admin']);
Route::post('/admin/commands/{command}/users/{user}/set-commander', 'Admin\UserController@setCommander')->name('admin.users.set-commander');
Route::post('/start-game', 'Admin\SettingController@startGame')->name('start-game');
Route::post('/stop-game', 'Admin\SettingController@stopGame')->name('stop-game');
Route::get('/admin', 'Admin\MainController@index')->name('admin.index');
