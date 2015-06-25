<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeachersCoursesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('course_teacher', function(Blueprint $table)
		{
			$table->integer('teacher_id')->unsigned();
                    $table->foreign('teacher_id')->references('teacher_id')
                            ->on('teachers')
                            ->onDelete('cascade');
                    $table->tinyInteger('course_id')->unsigned();
                    $table->foreign('course_id')->references('course_id')
                            ->on('courses')
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
		Schema::drop('course_teacher');
	}

}
