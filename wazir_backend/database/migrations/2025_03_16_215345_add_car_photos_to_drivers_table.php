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
            // Добавляем поля только если их нет
            if (!Schema::hasColumn('drivers', 'car_front')) {
                $table->string('car_front')->nullable();
            }
            
            if (!Schema::hasColumn('drivers', 'car_back')) {
                $table->string('car_back')->nullable();
            }
            
            if (!Schema::hasColumn('drivers', 'car_left')) {
                $table->string('car_left')->nullable();
            }
            
            if (!Schema::hasColumn('drivers', 'car_right')) {
                $table->string('car_right')->nullable();
            }
            
            if (!Schema::hasColumn('drivers', 'car_inside')) {
                $table->string('car_inside')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            $columns = ['car_front', 'car_back', 'car_left', 'car_right', 'car_inside'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('drivers', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
