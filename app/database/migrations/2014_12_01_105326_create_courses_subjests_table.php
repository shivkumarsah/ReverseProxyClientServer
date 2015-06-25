<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesSubjestsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_subject', function(Blueprint $table) {
                    $table->tinyInteger('course_id')->unsigned();
                    $table->foreign('course_id')->references('course_id')
                            ->on('courses')
                            ->onDelete('cascade');
                    $table->tinyInteger('subject_id')->unsigned();
                    $table->foreign('subject_id')->references('subject_id')
                            ->on('subjects')
                            ->onDelete('cascade');
                });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('course_subject');
    }

}
