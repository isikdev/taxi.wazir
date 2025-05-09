<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddDateOfBirthColumn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'drivers:add-date-of-birth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Добавляет колонку date_of_birth в таблицу drivers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Начинаем добавление колонки date_of_birth в таблицу drivers...');

        try {
            if (!Schema::hasColumn('drivers', 'date_of_birth')) {
                DB::statement('ALTER TABLE drivers ADD COLUMN date_of_birth DATE NULL AFTER phone');
                $this->info('Колонка date_of_birth успешно добавлена в таблицу drivers.');
            } else {
                $this->info('Колонка date_of_birth уже существует в таблице drivers.');
            }
        } catch (\Exception $e) {
            $this->error('Произошла ошибка при добавлении колонки: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
} 