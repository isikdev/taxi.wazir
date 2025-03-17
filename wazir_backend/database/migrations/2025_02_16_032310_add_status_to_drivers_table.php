<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('drivers', function (Blueprint $table) {
            // Проверяем существует ли уже колонка status
            if (!Schema::hasColumn('drivers', 'status')) {
                $table->string('status')->nullable()->default('offline');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            // Проверяем существует ли колонка status перед удалением
            if (Schema::hasColumn('drivers', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};