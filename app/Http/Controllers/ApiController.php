<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\DailyAssetHistory;
use App\DailyRateHistory;
use App\HourlyRateHistory;
use App\CurrentTotalAmount;

class ApiController extends Controller{

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

	public function coinRatio(){
		$current_total_amount_model = new CurrentTotalAmount;
		$current_amount = $current_total_amount_model::where('user_id', Auth::id())->first();
		$total_amount = $current_amount->amount;
		$bitflyerController = new BitflyerController;
		$bitflyer_assets = $bitflyerController->setAssetParams();
		$asset_params['bitflyer'] = $bitflyer_assets;
		$coincheckController = new CoincheckController;
		$coincheck_assets = $coincheckController->setAssetParams();
		$asset_params['coincheck'] = $coincheck_assets;
		$zaifController = new ZaifController;
		$zaif_assets = $zaifController->setAssetParams();
		$asset_params['zaif'] = $zaif_assets;

		$coin_ratio = [];
		$coin_ratio += (array)config('BitflyerCoins');
		$coin_ratio += (array)config('CoincheckCoins');
		$coin_ratio += (array)config('ZaifCoins');
		$coin_ratio['JPY'] = 0;
		$coin_ratio['others'] = 0;
		foreach ($coin_ratio as $coin_name => $v) {
			$coin_ratio[$coin_name] = 0;
		}

		foreach ($asset_params as $exchange => $coin_info) {
			if(!empty($coin_info['coin'])) {
				foreach ($coin_info['coin'] as $coin) {
					$ratio = num2per($coin['convert_JPY'], $total_amount);

					//5%以下のコインはothersにまとめる
					if($ratio < 5){
						$coin_ratio['others'] += $ratio;
					}else{
						$coin_ratio[$coin['coin_name']] += $ratio;
					}
				}
			}
		}

		//所有割合が上位5つのコインのみ表示
		arsort($coin_ratio);
		$coin_ratio = array_slice($coin_ratio, 0, 5);
		$coin_ratio = array_filter($coin_ratio);

		return json_encode($coin_ratio);
	}

	public function coinAmount() {
		$bitflyerController = new BitflyerController;
		$bitflyer_assets = $bitflyerController->setAssetParams();
		$asset_params['bitflyer'] = $bitflyer_assets;
		$coincheckController = new CoincheckController;
		$coincheck_assets = $coincheckController->setAssetParams();
		$asset_params['coincheck'] = $coincheck_assets;
		$zaifController = new ZaifController;
		$zaif_assets = $zaifController->setAssetParams();
		$asset_params['zaif'] = $zaif_assets;

		$coin_amount = [];
		$coin_amount += config('BitflyerCoins');
		$coin_amount += config('CoincheckCoins');
		$coin_amount += config('ZaifCoins');
		$coin_amount['JPY'] = 0;
		foreach ($coin_amount as $coin_name => $v) {
			$coin_amount[$coin_name] = 0;
		}
		foreach ($asset_params as $exchange => $coin_info) {
			foreach ($coin_info['coin'] as $coin) {
				$coin_amount[$coin['coin_name']] += $coin['convert_JPY'];
			}
		}

		return json_encode($coin_amount);
	}

	//amount + ratio
	public function coinAsset(){
		$current_total_amount_model = new CurrentTotalAmount;
		$current_amount = $current_total_amount_model::where('user_id', Auth::id())->first();
		$total_amount = $current_amount->amount;
		$bitflyerController = new BitflyerController;
		$bitflyer_assets = $bitflyerController->setAssetParams();
		$asset_params['bitflyer'] = $bitflyer_assets;
		$coincheckController = new CoincheckController;
		$coincheck_assets = $coincheckController->setAssetParams();
		$asset_params['coincheck'] = $coincheck_assets;
		$zaifController = new ZaifController;
		$zaif_assets = $zaifController->setAssetParams();
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

	public function dailyAssetHistory() {
		$asset_history_model = new DailyAssetHistory;
		$daily_asset_histories_array = $asset_history_model::where('user_id', Auth::id())->take(30)->get();

		$asset_array = [];
		foreach ($daily_asset_histories_array as $asset_history) {
			$asset_array[$asset_history->date] =  $asset_history->amount;
		}
		ksort($asset_array);
		$asset_histories_array = [];
		foreach ($asset_array as $date => $v) {
			$date = date('n/j', strtotime($date));
			$asset_histories_array[$date] = $v;
		}

		return $asset_histories_array;
	}

	public function coinPrice($exchange, $coin_name) {

	}

	public function coinDailyRateHistory($exchange, $coin_name) {
		$exchange_id = config('exchanges.'.$exchange);
		$daily_rate_history_model = new DailyRateHistory;
		$daily_rate_history = $daily_rate_history_model::where('exchange_id', $exchange_id)
			->where('coin_name', $coin_name)
			->where('date', '>', date('Y-m-d', strtotime('-30 day', time())) )
			->get();

		$rate_array = [];
		foreach ($daily_rate_history as $v) {
			$rate_array[$v->date] = $v->rate;
		}
		ksort($rate_array);
		$rate_history_array = [];
		foreach ($rate_array as $date => $v) {
			$date = date('n/j', strtotime($date));
			$rate_history_array[$date] = $v;
		}

		return json_encode($rate_history_array);
	}


	public function coinHourlyRateHistory($exchange, $coin_name) {
		$exchange_id = config('exchanges.'.$exchange);
		$hourly_rate_history_model = new HourlyRateHistory;
		$hourly_rate_history = $hourly_rate_history_model::where('exchange_id', $exchange_id)
			->where('coin_name', $coin_name)
			->where('date', '>', date('Y-m-d', strtotime('-24 hour', time())) )
			->get();

		$rate_array = [];
		foreach ($hourly_rate_history as $v) {
			$rate_array[$v->date] = $v->rate;
		}
		ksort($rate_array);
		$rate_history_array = [];

		foreach ($rate_array as $date => $v) {
			$date = date('H:00', strtotime($date));
			$rate_history_array[$date] = $v;
		}

		return json_encode($rate_history_array);
	}
}
