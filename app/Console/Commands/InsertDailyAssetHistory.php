<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BitflyerController;
use App\Http\Controllers\CoincheckController;
use App\Http\Controllers\ZaifController;
use App\DailyAssetHistory;
use App\CurrentTotalAmount;
use App\User;

class InsertDailyAssetHistory extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'InsertDailyAssetHistory';

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
		$users = User::get();
		foreach($users as $user){
			$current_total_amount_model = new CurrentTotalAmount;
			$user_id = $user->id;
			$current_amount = $current_total_amount_model::where('user_id', $user_id)->first();
			$asset_history_model = new DailyAssetHistory;
			$total_amount = $current_amount->amount;
			$asset_history_model->user_id = $user_id;
			$asset_history_model->amount = round($total_amount);
			$asset_history_model->date =  date('Y-m-d', strtotime('-2 day', time()));
			$asset_history_model->save();
		}
    }
}
