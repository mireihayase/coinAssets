<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BitflyerController;
use App\Http\Controllers\CoincheckController;
use App\Http\Controllers\ZaifController;
use App\MonthlyAssetHistory;
use App\User;

class InsertMonthlyAssetHistory extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'InsertMonthlyAssetHistory';

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
		$bitflyerController = new BitflyerController;
		$coincheckController = new CoincheckController;
		$zaifController = new ZaifController;

		$total_amount = 0;
		$users = User::get();
		foreach($users as $user) {
			$asset_history_model = new MonthlyAssetHistory;
			$user_id = $user->id;
			$bitflyer_assets = $bitflyerController->setAssetParams($user_id);
			$coincheck_assets = $coincheckController->setAssetParams($user_id);
			$zaif_assets = $zaifController->setAssetParams($user_id);
			$total_amount = $bitflyer_assets['total'] + $coincheck_assets['total'] + $zaif_assets['total'];
			$asset_history_model->user_id = $user_id;
			$asset_history_model->amount = round($total_amount);
			$asset_history_model->date =  date('Y-m-1');
			$asset_history_model->save();
		}
    }
}
