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
            // Добавляем поле баланса только если оно не существует
            if (!Schema::hasColumn('drivers', 'balance')) {
                $table->decimal('balance', 10, 2)->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            // Удаляем поле баланса только если оно существует
            if (Schema::hasColumn('drivers', 'balance')) {
                $table->dropColumn('balance');
            }
        });
    }
};
