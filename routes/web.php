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
    return view('auth.login');
});

Auth::routes([
	'register' => false,
	'reset' => false,
	'confirm' => false,
	'verify' => false,
]);

Route::get('/logout', 'Auth\LoginController@logout');

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('/admin/commands', 'Admin\CommandController', ['as' => 'admin']);
Route::resource('/admin/commands/{command}/users', 'Admin\UserController', ['as' => 'admin']);
Route::post('/admin/commands/{command}/users/{user}/set-commander', 'Admin\UserController@setCommander')->name('admin.users.set-commander');
Route::resource('/admin/stocks', 'Admin\StockController', ['as' => 'admin']);
Route::get('/admin/settings', 'Admin\SettingController@index')->name('admin.settings.index');
Route::post('/switch-game', 'Admin\SettingController@switchGame')->name('switch-game');
Route::get('/admin/switch-pause', 'Admin\SettingController@switchPause')->name('switch-pause');
Route::get('/admin', 'Admin\MainController@index')->name('admin.index');
Route::post('/admin/commands/{command}/clear-histories', 'Admin\CommandController@clearHistories')->name('admin.commands.clear-histories');
Route::post('/admin/stocks/{stock}/set-exchange', 'Admin\StockController@setExchange')->name('admin.stocks.set-exchange');
Route::post('/admin/stocks/import-quotations', 'Admin\StockController@importQuotations')->name('admin.stocks.import-quotations');
Route::post('/admin/stocks/{stock}/import-quotations-for-stock', 'Admin\StockController@importQuotationsForStock')->name('admin.stocks.import-quotations-for-stock');


Route::resource('/stocks', 'StockController');
Route::get('/test', 'HomeController@test');
Route::post('/commands/{command}/stocks/{stock}/buy', 'StockController@buy')->name('stocks.buy');
Route::post('/commands/{command}/stocks/{stock}/sell', 'StockController@sell')->name('stocks.sell');
