<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddLicenseNumberColumn extends Command
{
    protected $signature = 'drivers:add-license-number';
    protected $description = 'Добавляет колонку license_number в таблицу drivers';

    public function handle()
    {
        $this->info('Добавляем колонку license_number в таблицу drivers...');

        try {
            if (!Schema::hasColumn('drivers', 'license_number')) {
                DB::statement("ALTER TABLE drivers ADD COLUMN license_number VARCHAR(255) NULL AFTER date_of_birth");
                $this->info("Колонка license_number успешно добавлена.");
            } else {
                $this->info("Колонка license_number уже существует.");
            }
        } catch (\Exception $e) {
            $this->error('Произошла ошибка при добавлении колонки: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
} 