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
            // Документы водителя
            if (!Schema::hasColumn('drivers', 'license_number')) {
                $table->string('license_number', 20)->nullable()->after('phone');
            }
            
            if (!Schema::hasColumn('drivers', 'license_issue_date')) {
                $table->date('license_issue_date')->nullable()->after('license_number');
            }
            
            if (!Schema::hasColumn('drivers', 'license_expiry_date')) {
                $table->date('license_expiry_date')->nullable()->after('license_issue_date');
            }
            
            if (!Schema::hasColumn('drivers', 'personal_number')) {
                $table->string('personal_number', 17)->nullable()->after('license_expiry_date');
            }
            
            // Личные данные водителя
            if (!Schema::hasColumn('drivers', 'birthdate')) {
                $table->date('birthdate')->nullable()->after('personal_number');
            }
            
            if (!Schema::hasColumn('drivers', 'gender')) {
                $table->string('gender', 10)->nullable()->after('birthdate');
            }
            
            if (!Schema::hasColumn('drivers', 'language')) {
                $table->string('language', 30)->nullable()->after('gender');
            }
            
            // Адрес
            if (!Schema::hasColumn('drivers', 'country')) {
                $table->string('country', 30)->nullable()->after('language');
            }
            
            if (!Schema::hasColumn('drivers', 'city')) {
                $table->string('city', 30)->nullable()->after('country');
            }
            
            if (!Schema::hasColumn('drivers', 'address')) {
                $table->string('address')->nullable()->after('city');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('drivers', function (Blueprint $table) {
            // Удаляем поля
            $columns = [
                'license_number', 'license_issue_date', 'license_expiry_date', 'personal_number',
                'birthdate', 'gender', 'language', 'country', 'city', 'address'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('drivers', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};