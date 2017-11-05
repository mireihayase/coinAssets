<?php

namespace App\Http\Controllers\zaif;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller{
	public $api_key = '';
	public $api_secret = '';

	const API_URL = 'https://api.zaif.jp';
	const PUBLIC_BASE_URL = "https://api.zaif.jp/api/1";
	const TRADE_BASE_URL = "https://api.zaif.jp/tapi";
	const STREAMING_BASE_URL = "ws://api.zaif.jp:8888/stream";

	public function __construct(){
		$this->api_key = '';
		$this->api_secret = '';
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
		$response = self::getInfo();
		$this->data['assets'] = $response['return'];

		return view('zaif_assets', $this->data);
	}

	public function dispHistory(){
		$response = self::tradeHistory();
		array_shift($response);
		$this->data['history'] = $response;

		return view('zaif_history', $this->data);
	}
}
