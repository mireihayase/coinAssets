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
		$total_asset = 0;
		$bitflyerController = new BitflyerController;
		$bitflyer_assets = $bitflyerController->setAssetParams();
		$total_asset += $bitflyer_assets['total'];
		$asset_params['bitflyer'] = $bitflyer_assets;

		$coincheckController = new CoincheckController;
		$coincheck_assets = $coincheckController->setAssetParams();
		$total_asset += $coincheck_assets['total'];
		$asset_params['coincheck'] = $coincheck_assets;

		$zaifController = new ZaifController;
		$zaif_assets = $zaifController->setAssetParams();
		$total_asset += $zaif_assets['total'];
		$asset_params['zaif'] = $zaif_assets;

		$this->data['all_assets'] = $asset_params;
		$this->data['total_asset'] = $total_asset;

		return view('total_asset', $this->data);
	}

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
}
