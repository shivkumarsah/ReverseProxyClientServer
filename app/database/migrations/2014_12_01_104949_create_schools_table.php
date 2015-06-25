<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schools', function(Blueprint $table) {
                    $table->increments('school_id');
                    $table->string('school_name', 150);
                    $table->timestamps();
                    $table->index('school_name');
                });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::drop('schools');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

}
