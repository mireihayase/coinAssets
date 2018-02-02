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

Route::group(['middleware' => ['auth']], function () {

	Route::group(['prefix' => 'bitflyer'], function () {
		Route::get('/board', 'BitflyerController@getBoard');
		Route::get('/getmarkets', 'BitflyerController@getMarket');
		Route::get('/getbalance', 'BitflyerController@getBalance');
		Route::get('/getcoins', 'BitflyerController@getCoins');
		Route::get('/gethistogethistoryry', 'BitflyerController@getHistory');
		Route::get('/getcoinouts', 'BitflyerController@getCoinOuts');
		Route::get('/getchildorders', 'BitflyerController@getHistory');

		Route::get('/getticker', 'BitflyerController@getticker');

		Route::get('/getdeposits', 'BitflyerController@getDeposits');
		Route::get('/getwithdrawals', 'BitflyerController@getWithDraw');

		Route::get('/cumulativeProfit', 'BitflyerController@cumulativeProfit');

		Route::get('/storeCoinRate', 'BitflyerController@storeCoinRate');

		Route::get('/order', 'BitflyerController@order');

		Route::get('/setAssetParams', 'BitflyerController@setAssetParams');

		//表示
		Route::get('/history', 'BitflyerController@dispHistory');
	});

	Route::group(['prefix' => 'coincheck'], function () {
		Route::get('/board', 'CoincheckController@getBoard');

		Route::get('/getrate', 'CoincheckController@getRate');
		Route::get('/storeCoinRate', 'CoincheckController@storeCoinRate');

		Route::get('/getdeposits', 'CoincheckController@getDeposits');

		Route::get('/getbalance', 'CoincheckController@getBalance');
		Route::get('/transactions', 'CoincheckController@getTransaction');

		Route::get('/leverage_positions', 'CoincheckController@getLeveragePositions');
		Route::get('/setAssetParams', 'CoincheckController@setAssetParams');
		Route::get('/order', 'CoincheckController@order');

		//表示
		Route::get('/history', 'CoincheckController@dispHistory');
	});

	Route::group(['prefix' => 'zaif'], function () {
		Route::get('/get_info', 'ZaifController@getInfo');
		Route::get('/trade_history', 'ZaifController@tradeHistory');

		Route::get('/getrate', 'ZaifController@getRate');

		Route::get('/order', 'ZaifController@order');
		Route::get('/setAssetParams', 'ZaifController@setAssetParams');
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
	Route::get('/coin_ratio', 'ShowController@coinRatio');
	Route::get('/asset_history', 'ShowController@dailyAssetHistory');

	Route::get('/api/coin_ratio', 'ApiController@coinRatio');
	Route::get('/api/amount', 'ApiController@coinAmount');
	Route::get('/api/daily_asset_history', 'ApiController@dailyAssetHistory');

//	Route::get('/api/coin_price/{exchange}/{coin_name}', 'ApiController@coinPrice');

	Route::get('/api/daily_rate/{exchange}/{coin_name}', 'ApiController@coinDailyRateHistory');
	Route::get('/api/hourly_rate/{exchange}/{coin_name}', 'ApiController@coinHourlyRateHistory');

	Route::get('/price_list', 'ShowController@priceList');
	Route::get('/rate_history', 'ShowController@rateHistory');

});

