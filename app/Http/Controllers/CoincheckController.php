<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Api;
use Illuminate\Support\Facades\Redis;

class CoincheckController extends Controller{
	public $data;

	public $api_key = '';
	public $api_secret = '';

	const API_URL = 'https://coincheck.com';

	public function __construct(){

	}

	public function setParameter(){
		$exchange_id = config('exchanges.coincheck');
		$this->data['exchange_id'] = $exchange_id;
		$user_id = Auth::id();
		$api_model = new Api;
		$api = $api_model::where('user_id', $user_id)->where('exchange_id', $exchange_id)->first();

		$this->api_key = !empty($api) ? $api->api_key : '';
		$this->api_secret = !empty($api) ? $api->api_secret : '';
		$this->data['user_name'] = Auth::user()->name;
	}

	public function createApi(){
		self::setParameter();
		$this->data['api_key'] = !empty($this->api_key) ? $this->api_key : 'API Keyを入力してください';
		$this->data['api_secret'] = !empty($this->api_secret)? $this->api_secret : 'API Secretを入力してください';

		return view('regist_api', $this->data);
	}

	public function registApi(Request $request){
		$api_model = new Api;
		$user_id = Auth::id();
		$exchange_id = config('exchanges.coincheck');
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
		exit;
	}

	public function generateHeader($path, $query_data = null){
		$nonce = time();
		$url = self::API_URL . $path;
		$message = $nonce . $url . $query_data;
		$signature = hash_hmac("sha256", $message, $this->api_secret);
		$header = array(
			'ACCESS-KEY:' . $this->api_key,
			'ACCESS-NONCE:' . $nonce,
			'ACCESS-SIGNATURE:' . $signature,
		);

		return $header;
	}

	// 販売レート取得 @return string(rate)
	public static function getRate($coin_pair){
		$path = '/api/rate/';
		$url = self::API_URL . $path . $coin_pair;
		$response = self::getRequest($url);
		$response_aray = json_decode($response, true);

		return $response_aray['rate'];
	}


	public static function storeCoinRate(){
		$coin_rate_file = base_path() . '/ext/coin_rate.json';
		$coincheck_coins = config('CoincheckCoins');
		foreach ($coincheck_coins as $coin_name => $coin_pair){
			$rate = CoincheckController::getRate($coin_pair);
			$rate_arary[$coin_name] =$rate;
		}
		$rate_arary['JPY'] = 1;

		Redis::set('coincheck_rate', json_encode($rate_arary));
	}
	//注文
	/*
	 * $post_data = array(
	 *		'pair' => 'btc_jpy', //現在は 'btc_jpy' のみ
	 *		'order_type' => 'buy', //'buy','sell' * 指値,成行,レバレッジ取引新規,レバレッジ取引決済 8種類
	 *		'rate' => 30010.0,
	 *		'amount' => 0.1,
	 *		'market_buy_amount' => 30000, //成行時の日本円額
	 *		'position_id' => open, //レバレッジのポジション
	 *      'stop_loss_rate' =>
	 *	);
	 */
	public function order(){
		$path = 'api/exchange/orders';
		$url = self::API_URL . $path;
		self::setParameter();
		$header = self::generateHeader($path);
		$post_data = array(
	 		'pair' => 'btc_jpy', //現在は 'btc_jpy' のみ
	 		'order_type' => 'buy', //'buy','sell' * 指値,成行,レバレッジ取引新規,レバレッジ取引決済 8種類
	 		'rate' => 30010.0,
	 		'amount' => 0.1,
	 		'market_buy_amount' => 30000, //成行時の日本円額
	 		'position_id' => 'open', //レバレッジのポジション
	       	'stop_loss_rate' => ''
	 	);
		$response = self::curlPost($url, $header, $post_data);
		echo '<Pre>';
		var_dump('ExecFile: ' . basename(__FILE__) . '(' . __LINE__ . ')', 'FUNCTION: ' . __FUNCTION__);
		var_dump($response);
		exit;

		return $response;
	}

	//レバレッジ取引のポジション一覧
	public function getLeveragePositions(){
		$path = '/api/exchange/leverage/positions';
		$url = self::API_URL . $path;
		self::setParameter();
		$header = self::generateHeader($path);
		$response = self::curlExec($url, $header);

		return $response;
	}

	//残高取得
	public function getBalance(){
		$path = '/api/accounts/balance';
		$url = self::API_URL . $path;
		self::setParameter();
		$header = self::generateHeader($path);
		$response = self::curlExec($url, $header);

		return $response;
	}

	public function setAssetParams(){
		self::setParameter();
		$response = self::getBalance();
		$coin_rate = Redis::get('coincheck_rate');
		$coin_rate = (array)json_decode($coin_rate);
		$coincheck_coins = config('CoincheckCoins');

		$asset_data = [];
		$coin_asset = [];
		foreach ($coincheck_coins as $coin_name => $coin_pair){
			$coin_asset['coin_name'] = $coin_name;
			$coin_name_lower = mb_strtolower($coin_name);
			$coin_asset['amount'] = $response[$coin_name_lower];
			$coin_asset['convert_JPY'] = $coin_asset['amount'] * $coin_rate[$coin_name];
			$asset_data[] = $coin_asset;
		}

		return $asset_data;
	}

	//取引履歴取得
	public function getTransaction(){
		$path = '/api/exchange/orders/transactions';
		$url = self::API_URL . $path;
		self::setParameter();
		$header = self::generateHeader($path);
		$response = self::curlExec($url, $header);

		return $response;
	}

	public function dispAsset(){
		self::setParameter();
		$response = self::getBalance();
		array_shift($response);
		$assets_array = [];
		foreach ($response as $k => $v){
			if($v != 0){
				$assets_array[$k] = $v;
			}
		}
		$this->data['assets'] = $assets_array;

		return view('cc_assets', $this->data);
	}

	public function dispHistory(){
		self::setParameter();
		$response = self::getTransaction();
		$this->data['history'] = $response['transactions'];

		return view('cc_history', $this->data);
	}

}
