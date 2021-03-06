<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use App\DailyRateHistory;

class InsertDailyRate extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'InsertDailyRate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
		$bitflyer_coin_rate = Redis::get('bitflyer_rate');
		$bitflyer_coin_rate = (array)json_decode($bitflyer_coin_rate);
		unset($bitflyer_coin_rate['JPY']);
		$exchange_id = config('exchanges.bitflyer');
		foreach ($bitflyer_coin_rate as $coin_name => $rate){
			$daily_rate_history = new DailyRateHistory;
			$daily_rate_history->exchange_id = $exchange_id;
			$daily_rate_history->coin_name = $coin_name;
			$daily_rate_history->rate = $rate;
			$daily_rate_history->date = date('Y-m-d', strtotime('-1 day', time()));
			$daily_rate_history->save();
		}
		$coincheck_coin_rate = Redis::get('coincheck_rate');
		$coincheck_coin_rate = (array)json_decode($coincheck_coin_rate);
		unset($coincheck_coin_rate['JPY']);
		$exchange_id = config('exchanges.coincheck');
		foreach ($coincheck_coin_rate as $coin_name => $rate){
			$daily_rate_history = new DailyRateHistory;
			$daily_rate_history->exchange_id = $exchange_id;
			$daily_rate_history->coin_name = $coin_name;
			$daily_rate_history->rate = $rate;
			$daily_rate_history->date = date('Y-m-d', strtotime('-1 day', time()));
			$daily_rate_history->save();
		}
		$zaif_coin_rate = Redis::get('zaif_rate');
		$zaif_coin_rate = (array)json_decode($zaif_coin_rate);
		unset($zaif_coin_rate['JPY']);
		$exchange_id = config('exchanges.zaif');
		foreach ($zaif_coin_rate as $coin_name => $rate){
			$daily_rate_history = new DailyRateHistory;
			$daily_rate_history->exchange_id = $exchange_id;
			$daily_rate_history->coin_name = $coin_name;
			$daily_rate_history->rate = $rate;
			$daily_rate_history->date = date('Y-m-d', strtotime('-1 day', time()));
			$daily_rate_history->save();
		}

		$daily_rate_history_model = new DailyRateHistory;
		$old_rate_history_array = $daily_rate_history_model::where('date', '<', date('Y-m-d H:00:00', strtotime('-30 day', time())) )
			->forceDelete();
    }
}
