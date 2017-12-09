<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Http\Controllers\Controller;
use App\Http\Controllers\BitflyerController;
use App\Http\Controllers\CoincheckController;
use App\Http\Controllers\ZaifController;

class getCoinRate extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getCoinRate';

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
    //TODO response error時の処理
    public function handle() {
		$coin_rate_file = base_path() . '/ext/coin_rate.json';
		$coincheck_coins = config('CoincheckCoins');
		foreach ($coincheck_coins as $coin_name => $coin_pair){
			$rate = CoincheckController::getRate($coin_pair);
			$rate_arary[$coin_name] =$rate;
		}

		//zaif のMONAのみ足りないので追加
		$rate = ZaifController::getRate('mona_jpy');
		$rate_arary['MONA'] =$rate;

		$rate_json = json_encode($rate_arary);
		file_put_contents($coin_rate_file, $rate_json);
    }
}
