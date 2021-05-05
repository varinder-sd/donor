<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('country_id')->nullable($value = true);
            $table->string('country_code')->nullable($value = true);
            $table->string('fips_code')->nullable($value = true);
            $table->string('iso2')->nullable($value = true);
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
        Schema::dropIfExists('states');
    }
}
