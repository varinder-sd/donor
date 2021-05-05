<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('iso3')->nullable($value = true);
            $table->string('iso2')->nullable($value = true);
            $table->string('phonecode')->nullable($value = true);
            $table->string('capital')->nullable($value = true);
            $table->string('currency')->nullable($value = true);
            $table->string('currency_symbol')->nullable($value = true);
            $table->string('tld')->nullable($value = true);
            $table->string('native')->nullable($value = true);
            $table->string('region')->nullable($value = true);
            $table->string('subregion')->nullable($value = true);
            $table->text('timezones')->nullable($value = true);
            $table->text('translations')->nullable($value = true);
            $table->string('latitude')->nullable($value = true);
            $table->string('longitude')->nullable($value = true);
            $table->string('emoji')->nullable($value = true);
            $table->string('emojiU')->nullable($value = true);
            $table->string('flag')->nullable($value = true);
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
        Schema::dropIfExists('countries');
    }
}
