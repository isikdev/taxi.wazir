<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddRemainingDriverColumns extends Command
{
    protected $signature = 'drivers:add-remaining-columns';
    protected $description = 'Добавляет оставшиеся недостающие колонки в таблицу drivers';

    public function handle()
    {
        $this->info('Начинаем добавление оставшихся недостающих колонок в таблицу drivers...');

        $columns = [
            'city' => 'VARCHAR(255) NULL',
            'car_photo' => 'VARCHAR(255) NULL',
            'license_photo' => 'VARCHAR(255) NULL',
            'car_interior_front' => 'VARCHAR(255) NULL',
            'car_interior_back' => 'VARCHAR(255) NULL',
            'lat' => 'DECIMAL(10, 7) NULL',
            'lng' => 'DECIMAL(10, 7) NULL',
            'rejection_reason' => 'TEXT NULL',
            'approved_at' => 'TIMESTAMP NULL'
        ];

        try {
            foreach ($columns as $column => $definition) {
                if (!Schema::hasColumn('drivers', $column)) {
                    DB::statement("ALTER TABLE drivers ADD COLUMN {$column} {$definition}");
                    $this->info("Колонка {$column} успешно добавлена.");
                } else {
                    $this->info("Колонка {$column} уже существует.");
                }
            }
            $this->info('Все оставшиеся недостающие колонки успешно добавлены.');
        } catch (\Exception $e) {
            $this->error('Произошла ошибка при добавлении колонок: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
} 