<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolsCoursesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_school', function(Blueprint $table) {
                    $table->integer('school_id')->unsigned();
                    $table->integer('course_id')->unsigned();
                    $table->unique(array('course_id','school_id'));
                });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('course_school');
    }

}
