<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddAllRemainingDriverColumns extends Command
{
    protected $signature = 'drivers:add-all-remaining';
    protected $description = 'Добавляет все оставшиеся колонки в таблицу drivers из запроса';

    public function handle()
    {
        $this->info('Добавляем все оставшиеся колонки из запроса INSERT в таблицу drivers...');

        $columns = [
            'license_number' => 'VARCHAR(255) NULL',
            'license_issue_date' => 'DATE NULL',
            'license_expiry_date' => 'DATE NULL',
            'passport_front' => 'VARCHAR(255) NULL',
            'passport_back' => 'VARCHAR(255) NULL',
            'license_front' => 'VARCHAR(255) NULL',
            'license_back' => 'VARCHAR(255) NULL',
            'personal_number' => 'VARCHAR(255) NULL'
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
            $this->info('Все колонки из запроса INSERT успешно добавлены.');
        } catch (\Exception $e) {
            $this->error('Произошла ошибка при добавлении колонок: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
} 