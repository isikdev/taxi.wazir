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
        // Проверяем, существует ли таблица transactions
        if (!Schema::hasTable('transactions')) {
            Schema::create('transactions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('driver_id');
                $table->decimal('amount', 10, 2);
                $table->string('description')->nullable();
                $table->string('transaction_type')->default('deposit');
                $table->string('status')->default('completed');
                $table->string('payment_method')->nullable();
                $table->timestamps();
                
                // Не добавляем внешний ключ, чтобы избежать проблем с порядком миграций
                // $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
