<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Проверяем существование таблицы
        if (Schema::hasTable('notifications')) {
            // Получаем список колонок
            $columns = Schema::getColumnListing('notifications');
            
            Schema::table('notifications', function (Blueprint $table) use ($columns) {
                // Удаляем колонку name, если она существует
                if (in_array('name', $columns)) {
                    $table->dropColumn('name');
                }
                
                // Добавляем колонку user_id, если её нет
                if (!in_array('user_id', $columns)) {
                    $table->foreignId('user_id')->nullable()->after('id');
                }
                
                // Добавляем колонку read_at, если её нет
                if (!in_array('read_at', $columns)) {
                    $table->timestamp('read_at')->nullable();
                }
                
                // Преобразуем колонку read в read_at, если обе существуют
                if (in_array('read', $columns) && in_array('read_at', $columns)) {
                    // Получаем все прочитанные уведомления
                    $readNotifications = DB::table('notifications')
                        ->where('read', true)
                        ->whereNull('read_at')
                        ->get();
                    
                    // Устанавливаем read_at для прочитанных уведомлений
                    foreach ($readNotifications as $notification) {
                        DB::table('notifications')
                            ->where('id', $notification->id)
                            ->update(['read_at' => now()]);
                    }
                    
                    // Удаляем колонку read
                    $table->dropColumn('read');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Обратная миграция не требуется
    }
};
