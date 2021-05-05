<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraFieldToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->after('email');
            $table->string('dob')->after('phone');
            $table->string('country')->after('dob');
            $table->string('state')->after('country');
            $table->string('district')->after('state');
            $table->string('city')->after('district');
            $table->string('gender')->after('city');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn('phone');
            $table->dropColumn('dob');
            $table->dropColumn('country');
            $table->dropColumn('state');
            $table->dropColumn('district');
            $table->dropColumn('city');
            $table->dropColumn('gender');
            
        });
    }
}
