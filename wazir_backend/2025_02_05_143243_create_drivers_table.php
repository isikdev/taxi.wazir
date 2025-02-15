<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
	    $table->id();
	    $table->string('full_name');
	    $table->string('phone', 50)->nullable();
 	    $table->string('city', 100)->nullable();
	    $table->string('license_number', 100);
	    $table->date('license_issue_date')->nullable();
	    $table->date('license_expiry_date')->nullable();
	    $table->timestamps();
	});

    }

    public function down()
    {
        Schema::dropIfExists('drivers');
    }
};
