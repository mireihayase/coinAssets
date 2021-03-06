<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\getCoinRate',
		'App\Console\Commands\InsertDailyAssetHistory',
		'App\Console\Commands\InsertMonthlyAssetHistory',
		'App\Console\Commands\InsertHourlyRate',
		'App\Console\Commands\InsertDailyRate',
		'App\Console\Commands\InsertCurrentTotalAmount'
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
	 	$schedule->command('getCoinRate')
			 ->everyMinute()
			 ->withoutOverlapping()
			 ->evenInMaintenanceMode();
		$schedule->command('InsertCurrentTotalAmount')
			->everyFiveMinutes()
			->withoutOverlapping()
			->evenInMaintenanceMode();
		$schedule->command('InsertDailyAssetHistory')
			->daily();
		$schedule->command('InsertMonthlyAssetHistory')
			->monthly();
		$schedule->command('InsertHourlyRate')
			->hourly();
		$schedule->command('InsertDailyRate')
			->daily();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
