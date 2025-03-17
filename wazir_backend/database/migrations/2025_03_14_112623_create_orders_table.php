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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->string('waybill')->nullable();
            $table->string('phone')->nullable();
            $table->string('client_name')->nullable();
            $table->string('tariff')->nullable();
            $table->string('payment_method')->nullable();
            
            // Адрес отправления
            $table->string('origin_street')->nullable();
            $table->string('origin_house')->nullable();
            $table->string('origin_district')->nullable();
            
            // Адрес назначения
            $table->string('destination_street')->nullable();
            $table->string('destination_house')->nullable();
            $table->string('destination_district')->nullable();
            
            // Данные маршрута
            $table->string('distance')->nullable();
            $table->string('duration')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            
            // Дополнительные данные
            $table->text('notes')->nullable();
            $table->enum('status', ['new', 'in_progress', 'completed', 'cancelled'])->default('new');
            
            // ID водителя, если назначен
            $table->unsignedBigInteger('driver_id')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
