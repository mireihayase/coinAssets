<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Api;
use Illuminate\Support\Facades\Redis;

class ZaifController extends Controller{
	public $api_key = '';
	public $api_secret = '';

	const API_URL = 'https://api.zaif.jp';
	const PUBLIC_BASE_URL = "https://api.zaif.jp/api/1";
	const TRADE_BASE_URL = "https://api.zaif.jp/tapi";
	const STREAMING_BASE_URL = "ws://api.zaif.jp:8888/stream";

	public function __construct(){

	}

	public function setParameter($user_id = null){
		$exchange_id = config('exchanges.zaif');
		$this->data['exchange_id'] = $exchange_id;
		$user_id = empty($user_id) ? Auth::id() : $user_id;
		$api_model = new Api;
		$api = $api_model::where('user_id', $user_id)->where('exchange_id', $exchange_id)->first();
		$this->api_key = !empty($api) ? $api->api_key : '';
		$this->api_secret = !empty($api) ? $api->api_secret : '';
	}

	public function createApi(){
		self::setParameter();
		$this->data['api_key'] = !empty($this->api_key) ? $this->api_key : 'API Keyを入力してください';
		$this->data['api_secret'] = !empty($this->api_key)? $this->api_secret : 'API Secretを入力してください';

		return view('regist_api', $this->data);
	}

	public function registApi(Request $request){
		$api_model = new Api;
		$user_id = Auth::id();
		$exchange_id = config('exchanges.zaif');
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

	// 販売レート取得 @return string(rate)
	public static function getRate($coin_pair){
		$path = '/last_price/';
		$url = self::PUBLIC_BASE_URL . $path . $coin_pair;
		$response = self::getRequest($url);
		$response_aray = json_decode($response, true);

		return $response_aray['last_price'];
	}

	public static function storeCoinRate(){
		$zaif_coins = config('ZaifCoins');
		foreach ($zaif_coins as $coin_name => $coin_pair){
			$rate = self::getRate($coin_pair);
			$rate_arary[$coin_name] = $rate;
		}
		$rate_arary['JPY'] = 1;

		Redis::set('zaif_rate', json_encode($rate_arary));
	}

	//残高取得
	public function getInfo($user_id = null){
		$nonce = time();
		$path = 'get_info';
		$postdata = array( "nonce" => $nonce, "method" => $path );
		self::setParameter($user_id);
		if( !empty( $prms ) ) {
			$postdata = array_merge( $postdata, $prms );
		}
		$postdata_query = http_build_query( $postdata );
		$sign = hash_hmac( 'sha512', $postdata_query, $this->api_secret);
		$header = array( "Sign: {$sign}", "Key: {$this->api_key}", );
		$data = self::curlPost( self::TRADE_BASE_URL, $header, $postdata_query);

		return $data;
	}

	public function setAssetParams($user_id = null){
		$response = self::getInfo($user_id);
		$coin_rate = Redis::get('zaif_rate');
		$coin_rate = (array)json_decode($coin_rate);

		$asset_data = [];
		$coin_asset = [];
		$total = 0;
		if($response['success'] == 1) {
			$ammount_array = $response['return']['funds'];
			foreach ($ammount_array as $coin_name => $amount) {
				$coin_name_upper = strtoupper($coin_name);
				$coin_asset['coin_name'] = $coin_name_upper;
				$coin_asset['amount'] = $amount;
				$coin_asset['convert_JPY'] = $amount * $coin_rate[$coin_name_upper];
				$total += $coin_asset['convert_JPY'];
				$asset_data['coin'][] = $coin_asset;
			}
			$asset_data['total'] = $total;
		}else{
			//TODO log追加
			$asset_data['total'] = 0;
		}

		return $asset_data;
	}

	//取引履歴を取得
	public function tradeHistory(){
		$nonce = time();
		$path = 'trade_history';
		$postdata = array( "nonce" => $nonce, "method" => $path );
		if( !empty( $prms ) ) {
			$postdata = array_merge( $postdata, $prms );
		}
		$postdata_query = http_build_query( $postdata );
		$sign = hash_hmac( 'sha512', $postdata_query, $this->api_secret);
		$header = array( "Sign: {$sign}", "Key: {$this->api_key}", );
		$data = self::curlPost( self::TRADE_BASE_URL, $header, $postdata_query );

		return $data;
	}

	public function dispHistory(){
		self::setParameter();
		$response = self::tradeHistory();
		array_shift($response);
		$this->data['history'] = $response;

		return view('zaif_history', $this->data);
	}
}
