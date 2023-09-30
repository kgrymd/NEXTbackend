<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->call('App\Http\Controllers\UnchartedChallengeController@store')
        // ->monthlyOn(1, '0:0');

        //↓確認用に一旦1時間おきに実行するようにしている。確認できたら↑に戻す。
        $schedule->call('App\Http\Controllers\UnchartedChallengeController@store')
            ->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
