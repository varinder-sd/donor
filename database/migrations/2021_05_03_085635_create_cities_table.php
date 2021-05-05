<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('state_id')->nullable($value = true);
            $table->string('state_code')->nullable($value = true);
            $table->bigInteger('country_id')->nullable($value = true);
            $table->string('country_code')->nullable($value = true);
            $table->string('latitude')->nullable($value = true);
            $table->string('longitude')->nullable($value = true);
            $table->tinyInteger('flag')->default(1);
            $table->string('wikiDataId')->nullable($value = true);
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
        Schema::dropIfExists('cities');
    }
}
