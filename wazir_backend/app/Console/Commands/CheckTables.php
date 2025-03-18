<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CheckTables extends Command
{
    protected $signature = 'tables:check';
    protected $description = 'Проверяет наличие таблиц и создает их при необходимости';

    public function handle()
    {
        $this->info('Начинаем проверку таблиц...');
        
        // Проверяем таблицу drivers
        if (!Schema::hasTable('drivers')) {
            $this->warn('Таблица drivers не существует, создаем...');
            
            Schema::create('drivers', function ($table) {
                $table->id();
                $table->string('full_name');
                $table->string('phone')->nullable();
                $table->decimal('balance', 10, 2)->default(0);
                $table->boolean('is_confirmed')->default(true);
                $table->string('status')->default('online');
                
                // Поля для автомобилей
                $table->string('car_brand')->nullable();
                $table->string('car_model')->nullable();
                $table->string('car_color')->nullable();
                $table->string('car_year')->nullable();
                $table->string('license_plate')->nullable();
                
                // Поля для статусов анкеты
                $table->string('survey_status')->nullable();
                
                // Координаты
                $table->decimal('lat', 10, 7)->nullable();
                $table->decimal('lng', 10, 7)->nullable();
                
                $table->timestamps();
            });
            
            $this->info('Таблица drivers успешно создана');
            
            // Создаем тестового водителя
            DB::table('drivers')->insert([
                'full_name' => 'Тестовый Водитель',
                'phone' => '+996555123456',
                'balance' => 1000,
                'car_brand' => 'Toyota',
                'car_model' => 'Camry',
                'car_color' => 'Белый',
                'car_year' => '2020',
                'license_plate' => 'A123BC',
                'survey_status' => 'approved',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            $this->info('Тестовый водитель создан');
        } else {
            $this->info('Таблица drivers существует');
            
            // Проверяем наличие полей для автомобилей и добавляем их при необходимости
            $requiredColumns = ['car_brand', 'car_model', 'car_color', 'car_year', 'survey_status'];
            $columnsMissing = false;
            
            foreach ($requiredColumns as $column) {
                if (!Schema::hasColumn('drivers', $column)) {
                    $columnsMissing = true;
                    $this->warn("Отсутствует колонка {$column} в таблице drivers");
                }
            }
            
            if ($columnsMissing) {
                $this->warn('В таблице drivers отсутствуют некоторые необходимые поля, добавляем их');
                
                // Сохраняем ссылку на текущий объект для использования в замыкании
                $command = $this;
                
                // Добавляем недостающие колонки
                Schema::table('drivers', function ($table) use ($requiredColumns, $command) {
                    foreach ($requiredColumns as $column) {
                        if (!Schema::hasColumn('drivers', $column)) {
                            $table->string($column)->nullable();
                            $command->info("Добавлена колонка {$column} в таблицу drivers");
                        }
                    }
                });
            }
            
            $this->info('Количество водителей: ' . DB::table('drivers')->count());
        }
        
        // Проверяем таблицу transactions
        if (!Schema::hasTable('transactions')) {
            $this->warn('Таблица transactions не существует, создаем...');
            
            Schema::create('transactions', function ($table) {
                $table->id();
                $table->unsignedBigInteger('driver_id');
                $table->decimal('amount', 10, 2);
                $table->string('description')->nullable();
                $table->string('transaction_type')->default('deposit');
                $table->string('status')->default('completed');
                $table->string('payment_method')->nullable();
                $table->timestamps();
            });
            
            $this->info('Таблица transactions успешно создана');
        } else {
            $this->info('Таблица transactions существует');
            $this->info('Количество транзакций: ' . DB::table('transactions')->count());
        }
        
        // Выводим список всех таблиц в базе данных
        $tables = DB::select('SHOW TABLES');
        $this->info('Список всех таблиц в базе данных:');
        foreach ($tables as $table) {
            $tableName = reset($table);
            $this->line(' - ' . $tableName);
        }
        
        return Command::SUCCESS;
    }
} 