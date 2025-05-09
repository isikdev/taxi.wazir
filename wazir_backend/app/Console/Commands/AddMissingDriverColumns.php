<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddMissingDriverColumns extends Command
{
    protected $signature = 'drivers:add-missing-columns';
    protected $description = 'Добавляет все недостающие колонки в таблицу drivers';

    public function handle()
    {
        $this->info('Начинаем добавление недостающих колонок в таблицу drivers...');

        $columns = [
            'city' => 'VARCHAR(255) NULL',
            'car_photo' => 'VARCHAR(255) NULL',
            'car_brand' => 'VARCHAR(255) NULL',
            'car_model' => 'VARCHAR(255) NULL',
            'car_color' => 'VARCHAR(255) NULL',
            'car_year' => 'INT NULL',
            'transmission' => 'VARCHAR(255) NULL',
            'boosters' => 'BOOLEAN DEFAULT FALSE',
            'child_seat' => 'BOOLEAN DEFAULT FALSE',
            'parking_car' => 'BOOLEAN DEFAULT FALSE',
            'service_type' => 'VARCHAR(255) NULL',
            'category' => 'VARCHAR(255) NULL',
            'tariff' => 'DECIMAL(10,2) NULL',
            'tariff_extra' => 'DECIMAL(10,2) NULL',
            'vin' => 'VARCHAR(255) NULL',
            'body_number' => 'VARCHAR(255) NULL',
            'sts' => 'VARCHAR(255) NULL',
            'callsign' => 'VARCHAR(255) NULL',
            'has_nakleyka' => 'BOOLEAN DEFAULT FALSE',
            'has_lightbox' => 'BOOLEAN DEFAULT FALSE',
            'has_child_seat' => 'BOOLEAN DEFAULT FALSE',
            'car_front' => 'VARCHAR(255) NULL',
            'car_back' => 'VARCHAR(255) NULL',
            'car_left' => 'VARCHAR(255) NULL',
            'car_right' => 'VARCHAR(255) NULL',
            'interior_front' => 'VARCHAR(255) NULL',
            'interior_back' => 'VARCHAR(255) NULL'
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
            $this->info('Все недостающие колонки успешно добавлены.');
        } catch (\Exception $e) {
            $this->error('Произошла ошибка при добавлении колонок: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
} 