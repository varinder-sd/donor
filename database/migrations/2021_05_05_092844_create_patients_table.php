<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->default(0);
            $table->foreign('user_id')->references('id')->on('users');
            $table->text('patient_picture')->nullable($value = true);
            $table->tinyInteger('require_blood_or_plasma')->nullable($value = true)->default(2)->comment('1 is blood and 2 is plasma');
            $table->string('plasma_type')->nullable($value = true)->comment('1 is normal and 2 is covid recovered');
            $table->string('name')->nullable($value = true);
            $table->string('email')->nullable($value = true);
            $table->string('phone')->nullable($value = true);
            $table->string('date_of_birth')->nullable($value = true);
            $table->string('gender')->nullable($value = true);
            $table->unsignedBigInteger('blood_group_id')->default(0);
            $table->foreign('blood_group_id')->references('id')->on('blood_group');
            $table->unsignedBigInteger('country_id')->default(0);
            $table->foreign('country_id')->references('id')->on('countries');
            $table->unsignedBigInteger('state_id')->default(0);
            $table->foreign('state_id')->references('id')->on('states');
            $table->unsignedBigInteger('city_id')->default(0);
            $table->foreign('city_id')->references('id')->on('cities');
            $table->string('condition')->nullable($value = true);
            $table->string('patient_status')->nullable($value = true)->default(1);
            $table->string('admin_status')->nullable($value = true)->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patients');
    }
}
