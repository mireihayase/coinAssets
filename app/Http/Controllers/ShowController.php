<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BitflyerController;
use App\Http\Controllers\CoincheckController;
use App\Http\Controllers\ZaifController;
use Illuminate\Support\Facades\Redis;
use App\Api;
use App\DailyAssetHistory;


class ShowController extends Controller{

	public function getController($exchange){
		switch ($exchange){
			case 'bitflyer':
				$controller = new BitflyerController;
				break;
			case 'coincheck':
				$controller = new CoincheckController;
				break;
			case 'zaif':
				$controller = new ZaifController;
				break;
		}

		return $controller;
	}

	public function totalAsset(){
		$total_amount = 0;
		$bitflyerController = new BitflyerController;
		$bitflyer_assets = $bitflyerController->setAssetParams();
		$total_amount += $bitflyer_assets['total'];
		$asset_params['bitflyer'] = $bitflyer_assets;

		$coincheckController = new CoincheckController;
		$coincheck_assets = $coincheckController->setAssetParams();
		$total_amount += $coincheck_assets['total'];
		$asset_params['coincheck'] = $coincheck_assets;

		$zaifController = new ZaifController;
		$zaif_assets = $zaifController->setAssetParams();
		$total_amount += $zaif_assets['total'];
		$asset_params['zaif'] = $zaif_assets;

		$yesterday_amount = DailyAssetHistory::where('user_id', Auth::id())->whereDate('date',  date('Y-m-d', strtotime('-2 day', time())))->first();
		$daily_gain = $total_amount - $yesterday_amount->amount;

		$this->data['daily_gain'] = $daily_gain;
		$this->data['all_assets'] = $asset_params;
		$this->data['total_amount'] = $total_amount;

		return view('total_asset', $this->data);
	}

	//todo APIで共通化する
	public function coinRatio(){
		$total_amount = 0;
		$bitflyerController = new BitflyerController;
		$bitflyer_assets = $bitflyerController->setAssetParams();
		$total_amount += $bitflyer_assets['total'];
		$asset_params['bitflyer'] = $bitflyer_assets;
		$coincheckController = new CoincheckController;
		$coincheck_assets = $coincheckController->setAssetParams();
		$total_amount += $coincheck_assets['total'];
		$asset_params['coincheck'] = $coincheck_assets;
		$zaifController = new ZaifController;
		$zaif_assets = $zaifController->setAssetParams();
		$total_amount += $zaif_assets['total'];
		$asset_params['zaif'] = $zaif_assets;

		$coin_amount = [];
		$coin_amount += config('BitflyerCoins');
		$coin_amount += config('CoincheckCoins');
		$coin_amount += config('ZaifCoins');
		$coin_amount['JPY'] = 0;
		foreach ($coin_amount as $coin_name => $v) {
			$coin_amount[$coin_name] = [];
			$coin_amount[$coin_name]['convert_JPY'] = 0;
			$coin_amount[$coin_name]['amount'] = 0;
		}
		foreach ($asset_params as $exchange => $coin_info) {
			foreach ($coin_info['coin'] as $coin) {
				$coin_amount[$coin['coin_name']]['convert_JPY'] += $coin['convert_JPY'];
				$coin_amount[$coin['coin_name']]['amount'] += $coin['amount'];
			}
		}

		$this->data['total_amount'] = $total_amount;
		$this->data['amount'] = $coin_amount;

		return view('coin_ratio', $this->data);
	}

	public function DailyAssetHistory() {
		$total_amount = 0;
		$bitflyerController = new BitflyerController;
		$bitflyer_assets = $bitflyerController->setAssetParams();
		$total_amount += $bitflyer_assets['total'];
		$asset_params['bitflyer'] = $bitflyer_assets;
		$coincheckController = new CoincheckController;
		$coincheck_assets = $coincheckController->setAssetParams();
		$total_amount += $coincheck_assets['total'];
		$asset_params['coincheck'] = $coincheck_assets;
		$zaifController = new ZaifController;
		$zaif_assets = $zaifController->setAssetParams();
		$total_amount += $zaif_assets['total'];
		$this->data['total_amount'] = $total_amount;

		return view('asset_history', $this->data);
	}

	public function priceList(){
		$coin_rate_array = [];
		$bitflyer_coin_rate = Redis::get('bitflyer_rate');
		$bitflyer_coin_rate = (array)json_decode($bitflyer_coin_rate);
		unset($bitflyer_coin_rate['JPY']);
		$coin_rate_array['bitflyer'] = $bitflyer_coin_rate;
		$coincheck_coin_rate = Redis::get('coincheck_rate');
		$coincheck_coin_rate = (array)json_decode($coincheck_coin_rate);
		unset($coincheck_coin_rate['JPY']);
		$coin_rate_array['coincheck'] = $coincheck_coin_rate;
		$zaif_coin_rate = Redis::get('zaif_rate');
		$zaif_coin_rate = (array)json_decode($zaif_coin_rate);
		unset($zaif_coin_rate['JPY']);
		$coin_rate_array['zaif'] = $zaif_coin_rate;
		$this->data['coin_rate_array'] = $coin_rate_array;

		$total_amount = 0;
		$bitflyerController = new BitflyerController;
		$bitflyer_assets = $bitflyerController->setAssetParams();
		$total_amount += $bitflyer_assets['total'];
		$asset_params['bitflyer'] = $bitflyer_assets;
		$coincheckController = new CoincheckController;
		$coincheck_assets = $coincheckController->setAssetParams();
		$total_amount += $coincheck_assets['total'];
		$asset_params['coincheck'] = $coincheck_assets;
		$zaifController = new ZaifController;
		$zaif_assets = $zaifController->setAssetParams();
		$total_amount += $zaif_assets['total'];
		$this->data['total_amount'] = $total_amount;

		$yesterday_amount = DailyAssetHistory::where('user_id', Auth::id())->whereDate('date',  date('Y-m-d', strtotime('-2 day', time())))->first();
		$daily_gain = $total_amount - $yesterday_amount->amount;
		$this->data['daily_gain'] = $daily_gain;

		return view('price_list', $this->data);
	}

	// /$exchange/asset
	public function dispAsset($exchange){
		$controller = self::getController($exchange);
		$asset_params = $controller->setAssetParams();
		$this->data['assets'] = $asset_params;
		$this->data['exchange'] = $exchange;

		return view('assets', $this->data);
	}

	public function dispHistory(){
		self::setParameter();
		$response = self::getHistory();
		$this->data['history'] = $response;

		return view('history', $this->data);
	}

	public function createApi($exchange){
		$exchange_id = config('exchanges')[$exchange];
		$user_id = Auth::id();
		$api_model = new Api;
		$api = $api_model::where('user_id', $user_id)->where('exchange_id', $exchange_id)->first();
		$this->data['exchange_id'] = $exchange_id;
		$this->data['api_key'] = !empty($api->api_key) ? $api->api_key : '';
		$this->data['api_secret'] = !empty($api->api_secret)? $api->api_secret : '';

		return view('regist_api', $this->data);
	}

	public function registApi($exchange, Request $request){
		$api_model = new Api;
		$user_id = Auth::id();
		$exchange_id = config('exchanges')[$exchange];
		$api = $api_model::where('user_id', $user_id)->where('exchange_id', $exchange_id)->first();
		if(!empty($api)){
			$api_model = $api;
		}
		$api_model->api_key = $request->input('api_key');
		//TODO hash化する
		$api_model->api_secret = $request->input('api_secret');
		$api_model->user_id = Auth::id();
		$api_model->exchange_id = $request->input('exchange_id');
		$api_model->save();

		$this->data['api_key'] = $api_model->api_key;
		$this->data['api_secret'] = $api_model->api_secret;
		$this->data['exchange_id'] = $api_model->exchange_id;
		$this->data['message'] = 'APIの登録が完了しました。';

		return view('regist_api', $this->data);
	}


}
