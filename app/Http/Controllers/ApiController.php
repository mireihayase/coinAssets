<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ApiController extends Controller{

	public function coinRatio(){
		$total_amount = 0;
		$bitflyerController = new BitflyerController;
		$bitflyer_assets = $bitflyerController->setAssetParams();
		$total_amount += $bitflyer_assets['total'];
		$asset_params['bitflyer'] = $bitflyer_assets;
		$coincheckController = new CoincheckController;
		$coincheck_assets = $coincheckController->setAssetParams();
		$total_amount += $coincheck_assets['total'];
		$asset_params['coincheck'] = $coincheck_assets;
		$zaifController = new ZaifController;
		$zaif_assets = $zaifController->setAssetParams();
		$total_amount += $zaif_assets['total'];
		$asset_params['zaif'] = $zaif_assets;

		$coin_list = [];
		$coin_amount = [];
		$coin_amount += config('BitflyerCoins');
		$coin_amount += config('CoincheckCoins');
		$coin_amount += config('ZaifCoins');
		$coin_amount['JPY'] = 0;
		foreach ($coin_amount as $coin_name => $v) {
			$coin_amount[$coin_name] = 0;
		}

		$coin_ratio = [];
		foreach ($asset_params as $exchange => $coin_info) {
			foreach($coin_info['coin'] as $coin) {
				$coin_amount[$coin['coin_name']] += $coin['convert_JPY'];

				$ratio = num2per($coin['convert_JPY'], $total_amount);
				$coin_ratio[$coin['coin_name']] = $ratio;
			}
		}
		//所有割合が上位5つのコインのみ表示
		arsort($coin_ratio);
		$coin_ratio = array_slice($coin_ratio, 0, 5);
		$sum = array_sum($coin_ratio);
		$others = 100 - $sum;
		$coin_ratio['others'] = $others;

		$coin_asset = [];
		$coin_asset['ratio'] = $coin_ratio;
		$coin_asset['amount'] = $coin_amount;

		return json_encode($coin_asset);
	}
}
