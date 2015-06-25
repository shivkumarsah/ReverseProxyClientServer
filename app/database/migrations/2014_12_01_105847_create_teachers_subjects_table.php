<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeachersSubjectsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subject_teacher', function(Blueprint $table) {
                    $table->integer('teacher_id')->unsigned();
                    $table->foreign('teacher_id')->references('teacher_id')
                            ->on('teachers')
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
        Schema::drop('subject_teacher');
    }

}
