<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHourlyRateHistoryTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
		Schema::create('hourly_rate_histories', function (Blueprint $table) {
			$table->increments('id');
			$table->string('exchange_id');
			$table->string('coin_name');
			$table->string('rate');
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
		Schema::drop('hourly_rate_histories');
    }
}
