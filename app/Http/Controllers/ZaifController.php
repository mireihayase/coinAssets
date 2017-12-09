<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Api;

class ZaifController extends Controller{
	public $api_key = '';
	public $api_secret = '';

	const API_URL = 'https://api.zaif.jp';
	const PUBLIC_BASE_URL = "https://api.zaif.jp/api/1";
	const TRADE_BASE_URL = "https://api.zaif.jp/tapi";
	const STREAMING_BASE_URL = "ws://api.zaif.jp:8888/stream";

	public function __construct(){

	}

	public function setParameter(){
		$exchange_id = config('exchanges.zaif');
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

	//残高取得
	public function getInfo(){
		$nonce = time();
		$path = 'get_info';
		$postdata = array( "nonce" => $nonce, "method" => $path );
		if( !empty( $prms ) ) {
			$postdata = array_merge( $postdata, $prms );
		}
		$postdata_query = http_build_query( $postdata );
		$sign = hash_hmac( 'sha512', $postdata_query, $this->api_secret);
		$header = array( "Sign: {$sign}", "Key: {$this->api_key}", );
		$data = self::curlPost( self::TRADE_BASE_URL, $header, $postdata_query);

		return $data;
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

	public function dispAsset(){
		self::setParameter();
		$response = self::getInfo();
		$this->data['assets'] = $response['return'];

		return view('zaif_assets', $this->data);
	}

	public function dispHistory(){
		self::setParameter();
		$response = self::tradeHistory();
		array_shift($response);
		$this->data['history'] = $response;

		return view('zaif_history', $this->data);
	}
}
