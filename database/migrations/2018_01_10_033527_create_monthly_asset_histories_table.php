<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonthlyAssetHistoriesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
		Schema::create('monthly_asset_histories', function (Blueprint $table) {
			$table->increments('id');
			$table->string('user_id');
			$table->string('amount');
			$table->softDeletes();
			$table->datetime('date');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
		Schema::drop('monthly_asset_histories');
    }
}
