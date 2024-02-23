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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->command('test:pool')->everyMinute();
        $schedule->command('callaudit:sendmail')->dailyAt('00:01');
        $schedule->command('callaudit:notification')->dailyAt('02:30');
        $schedule->command('delete:purge')->weeklyOn(7, '23:00');
        $schedule->command('delete:audit_log')->daily();
        $schedule->command('incomplete:audit')->dailyAt('01:30');
        $schedule->command('tmp:pool')->dailyAt('20:00');
        $schedule->command('client:report')->dailyAt('01:00');
        $schedule->command('rawdump:report')->twiceDaily(1, 15);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
