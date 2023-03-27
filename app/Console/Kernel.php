<?php

namespace App\Console;

use App\Models\Today;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        Commands\DailyOperator::class,
    ];
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        // $schedule
        // ->call(function () {
        //     Today::query()->update(
        //         [
        //         'surgeon_id' => null,
        //         'cardiologist_id' => null,
        //         ]);
        // })
        // ->timezone('Asia/Yekaterinburg')
        // ->dailyAt('22:42');

        // $schedule
        // ->command('operator:daily')
        // ->timezone('Asia/Yekaterinburg')
        // // ->cron('34 23 * * *');
        // ->dailyAt('08:00');
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
