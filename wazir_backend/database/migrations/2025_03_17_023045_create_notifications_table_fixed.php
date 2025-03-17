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
        // Удаляем существующую таблицу notifications, если она существует
        if (Schema::hasTable('notifications')) {
            // Получаем и сохраняем существующие данные если они есть
            $existingData = DB::table('notifications')->get();
            
            // Удаляем таблицу
            Schema::dropIfExists('notifications');
        } else {
            $existingData = collect();
        }

        // Создаем таблицу notifications с правильной структурой
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->string('type'); // 'driver_application', 'balance', 'deletion', etc.
            $table->string('title');
            $table->json('data')->nullable(); // дополнительные данные
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        // Восстанавливаем данные, если они были
        if ($existingData->count() > 0) {
            foreach ($existingData as $notification) {
                $newData = [
                    'id' => $notification->id ?? null,
                    'user_id' => $notification->user_id ?? null,
                    'type' => $notification->type ?? 'unknown',
                    'title' => $notification->title ?? ($notification->message ?? 'Уведомление'),
                    'data' => $notification->data ?? json_encode(['message' => $notification->message ?? '']),
                    'read_at' => isset($notification->read) && $notification->read ? now() : $notification->read_at,
                    'created_at' => $notification->created_at ?? now(),
                    'updated_at' => $notification->updated_at ?? now(),
                ];
                
                // Фильтруем null значения из массива данных
                $newData = array_filter($newData, function ($value) {
                    return $value !== null;
                });
                
                DB::table('notifications')->insert($newData);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
