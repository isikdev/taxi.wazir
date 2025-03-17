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
                $table->foreignId('driver_id')->constrained()->onDelete('cascade');
                $table->decimal('amount', 10, 2);
                $table->string('description')->nullable();
                $table->enum('transaction_type', ['deposit', 'withdrawal', 'payment', 'refund'])->default('deposit');
                $table->enum('status', ['completed', 'pending', 'failed'])->default('completed');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Уже обрабатывается другой миграцией
    }
};