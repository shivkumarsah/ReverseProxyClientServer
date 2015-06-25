<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function(Blueprint $table) {
                    $table->increments('crs_id');
                    $table->integer('course_id')->unsigned();
                    $table->integer('school_id')->unsigned();
                    $table->string('course_name', 100);
                    $table->string('schools_id')->nullable();
                    $table->string('teachers_id')->nullable();
                    $table->string('subjects_id')->nullable();
                    $table->date('start_date');
                    $table->date('end_date');
                    $table->timestamps();
                    $table->index('course_name');
                    $table->unique(array('course_id','school_id'));
                });
        DB::statement("ALTER TABLE `courses` CHANGE `schools_id` `schools_id` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT 'Comma separated schools_ids. Has been kept for future prospect.'");                
        DB::statement("ALTER TABLE `courses` CHANGE `teachers_id` `teachers_id` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT 'Comma separated teachers ids. Has been kept for future prospect.'");        
        DB::statement("ALTER TABLE `courses` CHANGE `subjects_id` `subjects_id` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT 'Comma separated subjects ids. Has been kept for future prospect.'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('courses');
    }

}
