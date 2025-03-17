<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Проверяем, существует ли таблица drivers
        if (Schema::hasTable('drivers')) {
            // Проверяем, есть ли уже колонка city
            if (!Schema::hasColumn('drivers', 'city')) {
                Schema::table('drivers', function (Blueprint $table) {
                    $table->string('city', 100)->nullable()->after('phone');
                });
            }
        }
    }

    public function down()
    {
        // Удаляем колонку city, если она есть
        if (Schema::hasTable('drivers') && Schema::hasColumn('drivers', 'city')) {
            Schema::table('drivers', function (Blueprint $table) {
                $table->dropColumn('city');
            });
        }
    }
};