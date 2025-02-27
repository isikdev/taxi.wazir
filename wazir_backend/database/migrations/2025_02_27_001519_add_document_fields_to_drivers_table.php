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
            // Добавляем поля для хранения путей к фотографиям паспорта и водительского удостоверения,
            // если они еще не существуют
            if (!Schema::hasColumn('drivers', 'passport_front')) {
                $table->string('passport_front')->nullable();
            }
            if (!Schema::hasColumn('drivers', 'passport_back')) {
                $table->string('passport_back')->nullable();
            }
            if (!Schema::hasColumn('drivers', 'license_front')) {
                $table->string('license_front')->nullable();
            }
            if (!Schema::hasColumn('drivers', 'license_back')) {
                $table->string('license_back')->nullable();
            }

            // Добавляем поля для хранения путей к фотографиям автомобиля
            $table->string('car_front')->nullable();
            $table->string('car_back')->nullable();
            $table->string('car_left')->nullable();
            $table->string('car_right')->nullable();
            
            // Добавляем поля для хранения путей к фотографиям салона
            $table->string('interior_front')->nullable();
            $table->string('interior_back')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            // Удаляем только добавленные нами поля для фотографий автомобиля и салона
            $table->dropColumn([
                'car_front',
                'car_back',
                'car_left',
                'car_right',
                'interior_front',
                'interior_back'
            ]);
            
            // Не удаляем поля для паспорта и прав, так как они могли существовать ранее
        });
    }
};
