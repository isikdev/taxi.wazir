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
            // Добавляем поля координат только если они не существуют
            if (!Schema::hasColumn('drivers', 'lat')) {
                $table->decimal('lat', 10, 8)->nullable()->comment('Широта');
            }
            
            if (!Schema::hasColumn('drivers', 'lng')) {
                $table->decimal('lng', 11, 8)->nullable()->comment('Долгота');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            // Удаляем поля координат только если они существуют
            if (Schema::hasColumn('drivers', 'lat')) {
                $table->dropColumn('lat');
            }
            
            if (Schema::hasColumn('drivers', 'lng')) {
                $table->dropColumn('lng');
            }
        });
    }
};
