<?php

namespace App\Http\Controllers\coincheck;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Api;

class ApiController extends Controller{
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

	//残高取得
	public function getBalance(){
		$path = '/api/accounts/balance';
		$url = self::API_URL . $path;
		$header = self::generateHeader($path);
		$response = self::curlExec($url, $header);

		return $response;
	}

	//取引履歴取得
	public function getTransaction(){
		$path = '/api/exchange/orders/transactions';
		$url = self::API_URL . $path;
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
