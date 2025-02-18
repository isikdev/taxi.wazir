<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->string('personal_number', 17)->nullable()->after('license_expiry_date');

            $table->date('date_of_birth')->nullable()->after('personal_number');

            $table->string('passport_front')->nullable()->after('date_of_birth');
            $table->string('passport_back')->nullable()->after('passport_front');

            $table->string('license_front')->nullable()->after('passport_back');
            $table->string('license_back')->nullable()->after('license_front');

            $table->string('car_brand')->nullable()->after('license_back');
            $table->string('car_model')->nullable()->after('car_brand');
            $table->string('car_color')->nullable()->after('car_model');
            $table->year('car_year')->nullable()->after('car_color');
            
            $table->string('service_type')->nullable()->after('car_year');
            $table->string('category')->nullable()->after('service_type');
            $table->string('tariff')->nullable()->after('category');
            
            $table->string('license_plate')->nullable()->after('tariff');
            
            $table->boolean('has_nakleyka')->default(false)->after('license_plate');
            $table->boolean('has_lightbox')->default(false)->after('has_nakleyka');
            $table->boolean('has_child_seat')->default(false)->after('has_lightbox');
        });
    }

    public function down()
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn([
                'personal_number',
                'date_of_birth',
                'passport_front',
                'passport_back',
                'license_front',
                'license_back',
                'car_brand',
                'car_model',
                'car_color',
                'car_year',
                'service_type',
                'category',
                'tariff',
                'license_plate',
                'has_nakleyka',
                'has_lightbox',
                'has_child_seat',
            ]);
        });
    }
};