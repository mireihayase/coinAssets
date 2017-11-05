<?php

namespace App\Http\Controllers\coincheck;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller{
	public $data;

	public $api_key = '';
	public $api_secret = '';

	const API_URL = 'https://coincheck.com';

	public function __construct(){
		$this->api_key = '';
		$this->api_secret = '';
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
		$response = self::getTransaction();
		$this->data['history'] = $response['transactions'];

		return view('cc_history', $this->data);
	}

}
