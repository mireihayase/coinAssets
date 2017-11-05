<?php

namespace App\Http\Controllers\bitflyer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller{
	public $data;

	public $api_key = '';
	public $api_secret = '';
	const API_URL = 'https://api.bitflyer.jp';

	public function __construct(){
		$this->api_key = '';
		$this->api_secret = '';
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
		$response = self::getBalance();
		$this->data['assets'] = $response;

		return view('btf_assets', $this->data);
	}

	public function dispHistory(){
		$response = self::getHistory();
		$this->data['history'] = $response;

		return view('btf_history', $this->data);
	}
}
