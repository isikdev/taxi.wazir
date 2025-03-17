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
        // Добавляем запись в таблицу миграций, чтобы пометить проблемную миграцию как выполненную
        DB::table('migrations')->insert([
            'migration' => '2023_09_28_000000_add_survey_status_to_drivers',
            'batch' => DB::table('migrations')->max('batch') + 1,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
