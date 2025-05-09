<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddFinalDriverColumns extends Command
{
    protected $signature = 'drivers:add-final-columns';
    protected $description = 'Добавляет финальные недостающие колонки в таблицу drivers';

    public function handle()
    {
        $this->info('Начинаем добавление финальных недостающих колонок в таблицу drivers...');

        $columns = [
            'is_confirmed' => 'BOOLEAN DEFAULT FALSE'
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
            $this->info('Все финальные недостающие колонки успешно добавлены.');
        } catch (\Exception $e) {
            $this->error('Произошла ошибка при добавлении колонок: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
} 