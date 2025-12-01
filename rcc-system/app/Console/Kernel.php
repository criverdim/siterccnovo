<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // Aniversário diário às 08:00
        $schedule->call(function(){ \App\Jobs\SendBirthdayMessages::dispatch(); })->dailyAt('08:00');
        // Saudades semanal às 09:00
        $schedule->call(function(){ \App\Jobs\SendMissingAttendanceMessages::dispatch(); })->weeklyOn(1, '09:00');
    }
}

