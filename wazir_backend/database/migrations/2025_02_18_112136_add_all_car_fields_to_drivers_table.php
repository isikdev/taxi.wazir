<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('drivers', function (Blueprint $table) {
            // Если каких-то столбцов у вас уже нет - они добавятся.
            // Если некоторые столбцы уже существуют - Laravel выдаст ошибку при миграции,
            // поэтому можно сначала проверить через phpMyAdmin / DataGrip.

            // КПП, бустеры, child_seat (строковое), парковая машина, дополнительный тариф
            $table->string('transmission')->nullable()->after('car_year');
            $table->string('boosters')->nullable()->after('transmission');
            $table->string('child_seat')->nullable()->after('boosters');
            $table->string('parking_car')->nullable()->after('child_seat');
            $table->string('tariff_extra')->nullable()->after('tariff');

        });
    }

    public function down()
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn([
                'body_number',
                'sts',
                'callsign',
                'transmission',
                'boosters',
                'child_seat',
                'parking_car',
                'tariff_extra',
            ]);
        });
    }
};