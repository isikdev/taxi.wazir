<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Command
{
    protected $signature = 'create:notifications-table';
    protected $description = 'Создает таблицу notifications если она не существует';

    public function handle()
    {
        $this->info('Проверяем наличие таблицы notifications...');

        try {
            if (!Schema::hasTable('notifications')) {
                $this->info('Таблица notifications не существует. Создаем...');
                
                DB::statement("CREATE TABLE `notifications` (
                    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    `type` VARCHAR(255) NULL,
                    `title` VARCHAR(255) NULL,
                    `data` JSON NULL,
                    `read_at` TIMESTAMP NULL,
                    `created_at` TIMESTAMP NULL,
                    `updated_at` TIMESTAMP NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
                
                $this->info('Таблица notifications успешно создана.');
            } else {
                $this->info('Таблица notifications уже существует.');
            }
        } catch (\Exception $e) {
            $this->error('Произошла ошибка при создании таблицы: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
} 