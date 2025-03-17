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
        Schema::table('drivers', function (Blueprint $table) {
            // Проверяем существует ли уже колонка is_confirmed
            if (!Schema::hasColumn('drivers', 'is_confirmed')) {
                $table->boolean('is_confirmed')->default(false)->after('phone');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            // Проверяем существует ли колонка is_confirmed перед удалением
            if (Schema::hasColumn('drivers', 'is_confirmed')) {
                $table->dropColumn('is_confirmed');
            }
        });
    }
};