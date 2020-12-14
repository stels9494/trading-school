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

Auth::routes([
	'register' => false,
	'reset' => false,
	'confirm' => false,
	'verify' => false,
	'logout' => false,
]);
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

Route::group(['middleware' => 'auth'], function () {

	Route::get('/', function () {
		if (request()->user()->hasRole('admin'))
		{
			return redirect()->route('admin.index');
		}

		if (request()->user()->hasRole('member') || request()->user()->hasRole('commander'))
		{
			return redirect()->route('home');
		}
	})->name('index');

	// учатсник и капитан
	Route::group(['middleware' => 'role:commander|member'], function () {
		Route::get('/home', 'HomeController@index')->name('home');
		Route::resource('/stocks', 'StockController')->only(['show']);

		Route::group(['middleware' => 'role:commander'], function () {
			Route::post('/commands/{command}/stocks/{stock}/buy', 'StockController@buy')->name('stocks.buy');
			Route::post('/commands/{command}/stocks/{stock}/sell', 'StockController@sell')->name('stocks.sell');
		});
	});

	// админ панель
	Route::group(['middleware' => ['role:admin'], 'namespace' => 'Admin'], function () {

		Route::group(['prefix' => 'admin/', 'as' => 'admin.'], function () {
			Route::get('/', 'MainController@index')->name('index');
			Route::resource('/commands', 'CommandController');
			Route::resource('/commands/{command}/users', 'UserController');
			Route::post('/commands/{command}/users/{user}/set-commander', 'UserController@setCommander')->name('users.set-commander');
			Route::resource('/stocks', 'StockController');
			Route::get('/settings', 'SettingController@index')->name('settings.index');
			Route::get('/settings-live', 'SettingController@live')->name('live');
			Route::post('/commands/{command}/clear-histories', 'CommandController@clearHistories')->name('commands.clear-histories');
			Route::post('/stocks/{stock}/set-exchange', 'StockController@setExchange')->name('stocks.set-exchange');
			Route::post('/stocks/import-quotations', 'StockController@importQuotations')->name('stocks.import-quotations');
			Route::post('/stocks/{stock}/import-quotations-for-stock', 'StockController@importQuotationsForStock')->name('stocks.import-quotations-for-stock');
			Route::post('/change-password', 'UserController@changePassword')->name('users.change-password');
            Route::get('/switch-pause', 'SettingController@switchPause')->name('switch-pause');
            Route::get('/export-commands', 'CommandController@export')->name('commands.export');
		});

		Route::post('/switch-game', 'SettingController@switchGame')->name('switch-game');

        Route::post('/set-current', 'SettingController@setCurrent')->name('set-current');
	});
});
