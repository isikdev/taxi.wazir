<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddCarColumns extends Command
{
    protected $signature = 'drivers:add-car-columns';
    protected $description = 'Добавляет все колонки, связанные с автомобилем';

    public function handle()
    {
        $this->info('Добавляем колонки, связанные с автомобилем...');

        $columns = [
            'car_brand' => 'VARCHAR(255) NULL',
            'car_model' => 'VARCHAR(255) NULL',
            'car_color' => 'VARCHAR(255) NULL',
            'car_year' => 'INT NULL',
            'service_type' => 'VARCHAR(255) NULL',
            'category' => 'VARCHAR(255) NULL',
            'tariff' => 'VARCHAR(255) NULL',
            'vin' => 'VARCHAR(255) NULL',
            'body_number' => 'VARCHAR(255) NULL',
            'sts' => 'VARCHAR(255) NULL',
            'callsign' => 'VARCHAR(255) NULL',
            'license_plate' => 'VARCHAR(255) NULL',
            'transmission' => 'VARCHAR(255) NULL',
            'boosters' => 'BOOLEAN DEFAULT FALSE',
            'child_seat' => 'BOOLEAN DEFAULT FALSE',
            'parking_car' => 'BOOLEAN DEFAULT FALSE',
            'has_nakleyka' => 'BOOLEAN DEFAULT FALSE',
            'has_lightbox' => 'BOOLEAN DEFAULT FALSE',
            'has_child_seat' => 'BOOLEAN DEFAULT FALSE'
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
            $this->info('Все колонки автомобиля успешно добавлены.');
        } catch (\Exception $e) {
            $this->error('Произошла ошибка при добавлении колонок: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
} 