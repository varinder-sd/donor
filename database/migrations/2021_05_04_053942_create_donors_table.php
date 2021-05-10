<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('name')->nullable($value = true);
            $table->string('email')->nullable($value = true);
            $table->string('self_or_others')->nullable($value = true);         
            $table->string('relationship')->nullable($value = true);        
            $table->string('date_of_birth')->nullable($value = true);
             $table->tinyInteger('donate_blood_or_plasma')->nullable($value = true)->default(2)->comment('1 is blood and 2 is plasma');
            $table->string('blood_group_id')->nullable($value = true);
            $table->string('covid_status')->nullable($value = true);
            $table->date('covid_postive_date')->nullable($value = true);
            $table->text('covid_postive_report')->nullable($value = true);
            $table->date('covid_negtive_date')->nullable($value = true);
            $table->text('covid_negtive_report')->nullable($value = true);
            $table->string('covid_postive_more_than_one')->nullable($value = true);
            $table->smallInteger('diet_status')->nullable($value = true);
            $table->smallInteger('smoking')->nullable($value = true);
            $table->smallInteger('alchohal')->nullable($value = true);
            $table->smallInteger('vegetarian')->nullable($value = true);
            $table->smallInteger('health_status')->nullable($value = true);
            $table->smallInteger('blood_pressure')->nullable($value = true);
            $table->smallInteger('thyroid')->nullable($value = true);
            $table->smallInteger('diabetes')->nullable($value = true);
            $table->smallInteger('other')->nullable($value = true);
            $table->text('other_description')->nullable($value = true);
            $table->string('longitude')->nullable($value = true);
            $table->string('latitude')->nullable($value = true);
            $table->string('address')->nullable($value = true);
            
            $table->unsignedBigInteger('district_id')->nullable($value = true)->default(0);
            $table->foreign('district_id')->references('id')->on('districts');
            $table->unsignedBigInteger('country_id')->default(0);
            $table->foreign('country_id')->references('id')->on('countries');
            $table->unsignedBigInteger('state_id')->default(0);
            $table->foreign('state_id')->references('id')->on('states');
            $table->unsignedBigInteger('city_id')->default(0);
            $table->foreign('city_id')->references('id')->on('cities');
            
            $table->integer('pin_code')->nullable($value = true);
            $table->string('alternate_mobile_number')->nullable($value = true);
            $table->string('donor_status')->nullable($value = true);
            $table->string('admin_status')->nullable($value = true);
            $table->string('want_to_donate_in_future')->nullable($value = true);
			$table->date('donate_date')->nullable($value = true);
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
        Schema::dropIfExists('donors');
    }
}
