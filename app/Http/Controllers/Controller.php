<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function curlExec($url, $header = null){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
		if(!empty($header)){
			curl_setopt($curl, CURLOPT_HTTPHEADER,$header) ;
		}
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
		$result =  curl_exec($curl);
		curl_close($curl);

		return json_decode($result, true);
	}

	public static function curlPost($url, $header, $postdata) {
		$ch = curl_init();
		$options = array(
			CURLOPT_URL => $url,
			CURLOPT_HEADER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $postdata,
			CURLOPT_HTTPHEADER => $header,
		);
		curl_setopt_array($ch, $options);
		$data = curl_exec($ch);
		curl_close($ch);

		return json_decode($data, true);
	}
}
