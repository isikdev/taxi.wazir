<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixNotificationsTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Исправление структуры таблицы notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Начинаю исправление таблицы notifications...');

        try {
            // Проверяем существование таблицы
            if (!Schema::hasTable('notifications')) {
                $this->error('Таблица notifications не существует!');
                return 1;
            }

            // Получаем список колонок
            $columns = Schema::getColumnListing('notifications');
            $this->info('Текущие колонки: ' . implode(', ', $columns));

            // Проверяем наличие проблемной колонки name
            if (in_array('name', $columns)) {
                $this->info('Удаляю колонку name...');
                Schema::table('notifications', function ($table) {
                    $table->dropColumn('name');
                });
                $this->info('Колонка name удалена.');
            }

            // Проверяем необходимые колонки
            $requiredColumns = ['user_id', 'type', 'title', 'data', 'read_at'];
            $missingColumns = array_diff($requiredColumns, $columns);

            if (!empty($missingColumns)) {
                $this->info('Добавляю отсутствующие колонки: ' . implode(', ', $missingColumns));
                
                Schema::table('notifications', function ($table) use ($missingColumns, $columns) {
                    if (in_array('user_id', $missingColumns)) {
                        $table->foreignId('user_id')->nullable();
                    }
                    
                    if (in_array('type', $missingColumns)) {
                        $table->string('type');
                    }
                    
                    if (in_array('title', $missingColumns)) {
                        $table->string('title');
                    }
                    
                    if (in_array('data', $missingColumns)) {
                        $table->json('data')->nullable();
                    }
                    
                    if (in_array('read_at', $missingColumns)) {
                        $table->timestamp('read_at')->nullable();
                    }
                });
                
                $this->info('Добавлены отсутствующие колонки.');
            }

            // Проверка на колонку read и преобразование в read_at если нужно
            if (in_array('read', $columns) && in_array('read_at', $columns)) {
                $this->info('Преобразую значения из read в read_at...');
                
                // Получаем все непрочитанные уведомления
                $unreadNotifications = DB::table('notifications')
                    ->where('read', true)
                    ->whereNull('read_at')
                    ->get();
                
                // Устанавливаем read_at для прочитанных уведомлений
                foreach ($unreadNotifications as $notification) {
                    DB::table('notifications')
                        ->where('id', $notification->id)
                        ->update(['read_at' => now()]);
                }
                
                $this->info('Преобразовано ' . count($unreadNotifications) . ' уведомлений.');
                
                // Удаляем колонку read
                Schema::table('notifications', function ($table) {
                    $table->dropColumn('read');
                });
                
                $this->info('Колонка read удалена.');
            }

            $this->info('Исправление таблицы notifications завершено успешно!');
            return 0;
        } catch (\Exception $e) {
            $this->error('Произошла ошибка: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        }
    }
}
