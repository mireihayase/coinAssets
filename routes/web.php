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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/rate_list', 'HomeController@rateList');

//display template
Route::get('/archive', 'indexController@archive');
Route::get('/index', 'indexController@index');
Route::get('/dashboard', 'indexController@dashboard');
Route::get('/detail', 'indexController@detail');


Route::group(['middleware' => ['auth']], function () {

	Route::group(['prefix' => 'bitflyer'], function () {
		Route::get('/board', 'BitflyerController@getBoard');
		Route::get('/getmarkets', 'BitflyerController@getMarket');
		Route::get('/getbalance', 'BitflyerController@getBalance');
		Route::get('/getcoins', 'BitflyerController@getCoins');
		Route::get('/gethistogethistoryry', 'BitflyerController@getHistory');
		Route::get('/getcoinouts', 'BitflyerController@getCoinOuts');
		Route::get('/getchildorders', 'BitflyerController@getHistory');

		Route::get('/order', 'BitflyerController@order');


		//表示
		Route::get('/history', 'BitflyerController@dispHistory');
	});

	Route::group(['prefix' => 'coincheck'], function () {
		Route::get('/board', 'CoincheckController@getBoard');
		Route::get('/getbalance', 'CoincheckController@getBalance');
		Route::get('/transactions', 'CoincheckController@getTransaction');

		Route::get('/leverage_positions', 'CoincheckController@getLeveragePositions');

		Route::get('/order', 'CoincheckController@order');

		//表示
		Route::get('/history', 'CoincheckController@dispHistory');
	});

	Route::group(['prefix' => 'zaif'], function () {
		Route::get('/get_info', 'ZaifController@getInfo');
		Route::get('/trade_history', 'ZaifController@tradeHistory');

		Route::get('/order', 'ZaifController@order');

		//表示
		Route::get('/history', 'ZaifController@dispHistory');
	});

	//表示
	Route::group(['prefix' => '{exchange}'], function ($exchange) {
		Route::get('/asset', 'ShowController@dispAsset');
//		Route::get('/history', 'ShowController@dispHistory');
		Route::get('/api', 'ShowController@createApi');
		Route::post('/api', 'ShowController@registApi');
	});
	Route::get('/total', 'ShowController@totalAsset');
	Route::get('/', 'ShowController@totalAsset');
	Route::get('/rate_list', 'ShowController@rateList');

});

