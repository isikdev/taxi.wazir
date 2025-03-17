<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Driver;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class UpdateBalanceCache extends Command
{
    /**
     * Имя и сигнатура консольной команды.
     *
     * @var string
     */
    protected $signature = 'balance:update-cache';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Обновляет кэш общего баланса для более быстрой загрузки';

    /**
     * Создание нового экземпляра команды.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Выполнение консольной команды.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $startTime = microtime(true);
            
            // Подсчет общего баланса
            $totalBalance = Driver::sum('balance');
            
            // Сохранение в кэш на 24 часа
            Cache::put('total_balance', $totalBalance, 86400);
            
            $endTime = microtime(true);
            $executionTime = ($endTime - $startTime) * 1000; // в миллисекундах
            
            $this->info("Кэш общего баланса успешно обновлен! Общий баланс: {$totalBalance}");
            $this->info("Время выполнения: {$executionTime} мс");
            
            // Логирование успешного обновления
            Log::info("Кэш общего баланса успешно обновлен", [
                'total_balance' => $totalBalance,
                'execution_time_ms' => $executionTime
            ]);
            
            return 0;
        } catch (\Exception $e) {
            $this->error("Ошибка при обновлении кэша баланса: " . $e->getMessage());
            
            // Логирование ошибки
            Log::error("Ошибка при обновлении кэша баланса", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return 1;
        }
    }
} 