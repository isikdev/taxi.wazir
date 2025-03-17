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
        // Проверяем существование таблицы drivers перед модификацией
        if (Schema::hasTable('drivers')) {
            Schema::table('drivers', function (Blueprint $table) {
                // Проверяем наличие колонки перед добавлением
                if (!Schema::hasColumn('drivers', 'survey_status')) {
                    $table->string('survey_status')->default('pending')->after('is_confirmed');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('drivers') && Schema::hasColumn('drivers', 'survey_status')) {
            Schema::table('drivers', function (Blueprint $table) {
                $table->dropColumn('survey_status');
            });
        }
    }
}; 