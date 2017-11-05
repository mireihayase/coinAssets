<?php

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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//display template
Route::get('/archive', 'indexController@archive');
Route::get('/index', 'indexController@index');
Route::get('/dashboard', 'indexController@dashboard');
Route::get('/detail', 'indexController@detail');



Route::group(['namespace' => 'bitflyer'], function () {
	Route::group(['prefix' => 'bitflyer'], function () {
		Route::get('/board', 'ApiController@getBoard');
		Route::get('/getbalance', 'ApiController@getBalance');
		Route::get('/gethistory', 'ApiController@getHistory');
		Route::get('/getcoinouts', 'ApiController@getCoinOuts');

		//表示
		Route::get('/asset', 'ApiController@dispAsset');
		Route::get('/history', 'ApiController@dispHistory');
	});
});

Route::group(['namespace' => 'coincheck'], function () {
	Route::group(['prefix' => 'coincheck'], function () {
		Route::get('/board', 'ApiController@getBoard');
		Route::get('/getbalance', 'ApiController@getBalance');
		Route::get('/transactions', 'ApiController@getTransaction');

		//表示
		Route::get('/asset', 'ApiController@dispAsset');
		Route::get('/history', 'ApiController@dispHistory');
	});
});

Route::group(['namespace' => 'zaif'], function () {
	Route::group(['prefix' => 'zaif'], function () {
		Route::get('/get_info', 'ApiController@getInfo');
		Route::get('/trade_history', 'ApiController@tradeHistory');

		//表示
		Route::get('/asset', 'ApiController@dispAsset');
		Route::get('/history', 'ApiController@dispHistory');
	});
});

