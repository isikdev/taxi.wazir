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
        if (!Schema::hasTable('driver_vehicles')) {
            Schema::create('driver_vehicles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('driver_id')->constrained()->onDelete('cascade');
                $table->string('brand')->nullable();
                $table->string('model')->nullable();
                $table->string('year')->nullable();
                $table->string('color')->nullable();
                $table->string('license_plate')->nullable();
                $table->string('vin')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_vehicles');
    }
};
