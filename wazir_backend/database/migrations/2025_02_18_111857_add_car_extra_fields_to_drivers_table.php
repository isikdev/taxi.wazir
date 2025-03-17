<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('drivers', function (Blueprint $table) {
            // Добавляем дополнительные поля информации о машине только если они не существуют
            
            if (!Schema::hasColumn('drivers', 'vin')) {
                $table->string('vin')->nullable()->after('license_plate');
            }
            
            if (!Schema::hasColumn('drivers', 'license_number')) {
                $table->string('license_number')->nullable()->after('vin');
            }
            
            if (!Schema::hasColumn('drivers', 'vehicle_registration')) {
                $table->string('vehicle_registration')->nullable()->after('license_number');
            }
            
            if (!Schema::hasColumn('drivers', 'insurance_number')) {
                $table->string('insurance_number')->nullable()->after('vehicle_registration');
            }
            
            if (!Schema::hasColumn('drivers', 'insurance_expiry_date')) {
                $table->date('insurance_expiry_date')->nullable()->after('insurance_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('drivers', function (Blueprint $table) {
            // Удаляем поля, если они существуют
            $columns = ['vin', 'license_number', 'vehicle_registration', 'insurance_number', 'insurance_expiry_date'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('drivers', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};