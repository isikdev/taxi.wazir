<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('drivers', function (Blueprint $table) {
            // Добавляем новые поля
            $table->string('vin')->nullable()->after('license_plate');
            $table->string('body_number')->nullable()->after('vin');
            $table->string('sts')->nullable()->after('body_number');
            $table->string('callsign')->nullable()->after('sts');
            
            // Если нужно, можно добавить transmission, boosters, child_seat и т.д.
            // $table->string('transmission')->nullable()->after('car_year');
            // $table->string('boosters')->nullable()->after('transmission');
            // $table->string('child_seat')->nullable()->after('boosters');
            // $table->string('parking_car')->nullable()->after('child_seat');
            // $table->string('tariff_extra')->nullable()->after('tariff');
            // и т.д.
        });
    }

    public function down()
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn(['vin', 'body_number', 'sts', 'callsign']);
            // И любые другие поля, которые вы добавляли
        });
    }
};