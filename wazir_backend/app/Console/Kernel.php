<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Определяем команды консоли для приложения.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\UpdateBalanceCache::class,
        \App\Console\Commands\MigrateDriverImages::class,
    ];

    /**
     * Определяем расписание команд приложения.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Обновляем кэш баланса каждые 10 минут
        $schedule->command('balance:update-cache')
                 ->everyTenMinutes()
                 ->withoutOverlapping()
                 ->appendOutputTo(storage_path('logs/balance-cache.log'));
    }

    /**
     * Регистрируем команды для приложения.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
} 