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
            // Добавляем поле rejection_reason только если его нет
            if (!Schema::hasColumn('drivers', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            // Удаляем поле rejection_reason только если оно есть
            if (Schema::hasColumn('drivers', 'rejection_reason')) {
                $table->dropColumn('rejection_reason');
            }
        });
    }
};
