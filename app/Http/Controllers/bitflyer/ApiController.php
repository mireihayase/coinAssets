<?php

namespace App\Http\Controllers\bitflyer;

use App\Providers\AuthServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Api;

class ApiController extends Controller{
	public $data;
	public $user_id;
	public $user_name;

	public $api_key = '';
	public $api_secret = '';
	const API_URL = 'https://api.bitflyer.jp';

	public function __construct(){

	}

	public function setParameter(){
		$exchange_id = config('exchanges.bitflyer');
		$this->data['exchange_id'] = $exchange_id;
		$user_id = Auth::id();
		$api_model = new Api;
		$api = $api_model::where('user_id', $user_id)->where('exchange_id', $exchange_id)->first();

		$this->api_key = !empty($api) ? $api->api_key : '';
		$this->api_secret = !empty($api) ? $api->api_secret : '';
		$this->data['user_name'] = Auth::user()->name;
	}

	public function generateHeader($path, $query_data = null){
		$timestamp = time();
		$body = !empty($query_data) ? '?' . http_build_query($query_data) : '';
		$text = $timestamp . 'GET' . $path . $body;
//		$text = $timestamp . $method . $api . $body;
		$sign = hash_hmac('sha256', $text, $this->api_secret);
//		$header['http_headers'] = array(
		$header = array(
			'ACCESS-KEY:' . $this->api_key,
			'ACCESS-TIMESTAMP:' . $timestamp,
			'ACCESS-SIGN:' . $sign,
			'Content-Type:application/json',
			'Content-Length:'. strlen($body),
		);

		return $header;
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
		$exchange_id = config('exchanges.bitflyer');
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
		$this->data['user_name'] = Auth::user()->name;

		return view('regist_api', $this->data);
	}

	//板情報取得
	public function getBoard(){
		$path = '/v1/getboard';
		$url = self::API_URL . $path;
		$response = self::curlExec($url);

		exit;
	}

	//資産残高を取得
	public function getBalance(){
		$path = '/v1/me/getbalance';
		$url = self::API_URL . $path;
		$header = self::generateHeader($path);
		$response = self::curlExec($url, $header);

		return $response;
	}

	//
	public function getHistory(){
		$path = '/v1/me/getcoinins';
//		$path = '/v1/me/getchildorders';
		$url = self::API_URL . $path;
		$header = self::generateHeader($path);
		$response = self::curlExec($url, $header);

		return $response;
	}

	//
	public function getCoinOuts(){
		$path = '/v1/me/getcoinouts';
		$url = self::API_URL . $path;
		$header = self::generateHeader($path);
		$response = self::curlExec($url, $header);

		return $response;
	}

	public function dispAsset(){
		self::setParameter();
		$response = self::getBalance();
		$this->data['assets'] = $response;

		return view('btf_assets', $this->data);
	}

	public function dispHistory(){
		self::setParameter();
		$response = self::getHistory();
		$this->data['history'] = $response;

		return view('btf_history', $this->data);
	}
}
