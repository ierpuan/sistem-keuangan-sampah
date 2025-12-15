<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\GenerateTagihanBulanan::class,
        Commands\UpdateStatusTagihan::class,
        Commands\AutoDebitDeposit::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        // Generate tagihan otomatis setiap tanggal 1 jam 00:00
        $schedule->command('tagihan:generate')->monthlyOn(1, '00:00');

        // Update status tagihan setiap hari jam 01:00
        $schedule->command('tagihan:update-status')->dailyAt('01:00');

        // Auto debit deposit setiap hari jam 02:00
        $schedule->command('deposit:auto-debit')->dailyAt('02:00');
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

}