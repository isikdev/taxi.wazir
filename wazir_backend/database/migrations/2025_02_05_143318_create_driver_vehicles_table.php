<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('driver_vehicles', function (Blueprint $table) {
            $table->id();
            // Связь с таблицей drivers (каждое транспортное средство принадлежит водителю)
            $table->unsignedBigInteger('driver_id');
            // Поля для автомобиля
            $table->string('vehicle_brand', 100)->nullable();
            $table->string('vehicle_model', 100)->nullable();
            $table->string('vehicle_color', 50)->nullable();
            $table->year('vehicle_year')->nullable();
            $table->string('vehicle_state_number', 50)->nullable();
            $table->string('chassis_number', 100)->nullable();
            $table->string('sts', 100)->nullable();
            $table->string('stir', 100)->nullable();
            $table->timestamps();

            // Внешний ключ: при удалении водителя удаляются и его автомобили
            $table->foreign('driver_id')
                ->references('id')->on('drivers')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('driver_vehicles');
    }
};
