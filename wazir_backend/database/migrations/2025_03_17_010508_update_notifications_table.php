<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Добавляем поле user_id, если его нет
            if (!Schema::hasColumn('notifications', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id');
            }
            
            // Перед удалением колонки убедимся, что она существует
            if (Schema::hasColumn('notifications', 'read')) {
                $table->dropColumn('read');
            }
            
            // Добавляем read_at, если его нет
            if (!Schema::hasColumn('notifications', 'read_at')) {
                $table->timestamp('read_at')->nullable()->after('data');
            }
            
            // Перед удалением колонки убедимся, что она существует
            if (Schema::hasColumn('notifications', 'message')) {
                $table->dropColumn('message');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Возвращаем поле message если оно не существует
            if (!Schema::hasColumn('notifications', 'message')) {
                $table->text('message')->after('title');
            }
            
            // Возвращаем поле read если оно не существует
            if (!Schema::hasColumn('notifications', 'read')) {
                $table->boolean('read')->default(false)->after('message');
            }
            
            // Убираем поле read_at если оно существует
            if (Schema::hasColumn('notifications', 'read_at')) {
                $table->dropColumn('read_at');
            }
            
            // Убираем поле user_id если оно существует
            if (Schema::hasColumn('notifications', 'user_id')) {
                $table->dropColumn('user_id');
            }
        });
    }
};
