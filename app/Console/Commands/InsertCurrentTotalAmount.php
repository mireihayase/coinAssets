<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BitflyerController;
use App\Http\Controllers\CoincheckController;
use App\Http\Controllers\ZaifController;
use App\CurrentTotalAmount;
use App\User;

class InsertCurrentTotalAmount extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'InsertCurrentTotalAmount';

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

		$users = User::get();
		foreach($users as $user){
			$current_total_amount_model = new CurrentTotalAmount;
			$user_id = $user->id;
			$current_amount = $current_total_amount_model::where('user_id', $user_id)->first();
			$current_amount = !empty($current_amount) ? $current_amount : $current_total_amount_model;
			$bitflyer_assets = $bitflyerController->setAssetParams($user_id);
			$coincheck_assets = $coincheckController->setAssetParams($user_id);
			$zaif_assets = $zaifController->setAssetParams($user_id);
			$total_amount = $bitflyer_assets['total'] + $coincheck_assets['total'] + $zaif_assets['total'];
			$current_amount->user_id = $user_id;
			$current_amount->amount = round($total_amount);
			$current_amount->save();
		}
    }
}
