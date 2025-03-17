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
            // Добавляем поля только если они не существуют
            if (!Schema::hasColumn('drivers', 'driver_license_front')) {
                $table->string('driver_license_front')->nullable();
            }
            
            if (!Schema::hasColumn('drivers', 'driver_license_back')) {
                $table->string('driver_license_back')->nullable();
            }
            
            if (!Schema::hasColumn('drivers', 'driver_passport_front')) {
                $table->string('driver_passport_front')->nullable();
            }
            
            if (!Schema::hasColumn('drivers', 'driver_passport_back')) {
                $table->string('driver_passport_back')->nullable();
            }
            
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
            
            if (!Schema::hasColumn('drivers', 'tech_passport_front')) {
                $table->string('tech_passport_front')->nullable();
            }
            
            if (!Schema::hasColumn('drivers', 'tech_passport_back')) {
                $table->string('tech_passport_back')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            // Удаляем поля только если они существуют
            $columns = [
                'driver_license_front',
                'driver_license_back',
                'driver_passport_front',
                'driver_passport_back',
                'car_front',
                'car_back',
                'car_left',
                'car_right',
                'car_inside',
                'tech_passport_front',
                'tech_passport_back'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('drivers', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
