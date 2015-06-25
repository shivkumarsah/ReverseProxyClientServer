<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsCoursesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_student', function(Blueprint $table) {
                    $table->tinyInteger('course_id')->unsigned();
                    $table->foreign('course_id')->references('course_id')
                            ->on('courses')
                            ->onDelete('cascade');
                    $table->integer('student_id')->unsigned();
                    $table->foreign('student_id')->references('student_id')
                            ->on('students')
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
        Schema::drop('course_student');
    }

}
