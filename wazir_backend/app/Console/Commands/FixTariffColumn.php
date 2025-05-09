<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixTariffColumn extends Command
{
    protected $signature = 'drivers:fix-tariff-column';
    protected $description = 'Исправляет тип данных колонки tariff в таблице drivers';

    public function handle()
    {
        $this->info('Исправляем тип данных колонки tariff...');

        try {
            if (Schema::hasColumn('drivers', 'tariff')) {
                // Сначала создаем временную колонку
                DB::statement("ALTER TABLE drivers ADD COLUMN tariff_temp VARCHAR(255) NULL");
                $this->info("Временная колонка tariff_temp создана.");
                
                // Затем переносим все данные из старой колонки в новую (если возможно)
                DB::statement("UPDATE drivers SET tariff_temp = tariff WHERE tariff IS NOT NULL");
                $this->info("Данные перенесены во временную колонку.");
                
                // Удаляем старую колонку
                DB::statement("ALTER TABLE drivers DROP COLUMN tariff");
                $this->info("Старая колонка tariff удалена.");
                
                // Переименовываем временную колонку
                DB::statement("ALTER TABLE drivers CHANGE tariff_temp tariff VARCHAR(255) NULL");
                $this->info("Колонка tariff успешно исправлена.");
            } else {
                DB::statement("ALTER TABLE drivers ADD COLUMN tariff VARCHAR(255) NULL");
                $this->info("Колонка tariff создана с правильным типом данных.");
            }
            
            $this->info('Тип данных колонки tariff успешно изменен на VARCHAR(255).');
        } catch (\Exception $e) {
            $this->error('Произошла ошибка при исправлении колонки: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
} 