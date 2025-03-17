<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('drivers', function (Blueprint $table) {
            // Добавляем поля только если они не существуют
            
            if (!Schema::hasColumn('drivers', 'transmission')) {
                $table->string('transmission')->nullable()->after('car_year');
            }
            
            if (!Schema::hasColumn('drivers', 'boosters')) {
                $table->string('boosters')->nullable()->after('transmission');
            }
            
            if (!Schema::hasColumn('drivers', 'child_seat')) {
                $table->string('child_seat')->nullable()->after('boosters');
            }
            
            if (!Schema::hasColumn('drivers', 'parking_car')) {
                $table->string('parking_car')->nullable()->after('child_seat');
            }
            
            if (!Schema::hasColumn('drivers', 'tariff_extra')) {
                $table->string('tariff_extra')->nullable()->after('tariff');
            }
        });
    }

    public function down()
    {
        Schema::table('drivers', function (Blueprint $table) {
            // Удаляем поля только если они существуют
            $columns = [
                'body_number',
                'sts',
                'callsign',
                'transmission',
                'boosters',
                'child_seat',
                'parking_car',
                'tariff_extra',
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('drivers', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};