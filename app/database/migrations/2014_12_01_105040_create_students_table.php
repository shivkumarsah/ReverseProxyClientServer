<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function(Blueprint $table) {
                    $table->increments('student_id');
                    $table->integer('school_id')->unsigned();
                    $table->foreign('school_id')->references('school_id')->on('schools');
                    $table->string('first_name', 100);
                    $table->string('last_name', 100);
                    $table->string('email', 100)->nullable();
                    $table->string('adusername', 100)->nullable();
                    $table->string('grade', 50)->nullable();
                    $table->timestamps();
                    $table->index('first_name');
                    $table->index('last_name');
                    $table->index('email');
                });
                
                
        DB::statement("ALTER TABLE `students` CHANGE `adusername` `adusername` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT 'MS Active Directory username'");
        DB::statement("ALTER TABLE `students` CHANGE `grade` `grade` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT 'use 1-12 for grades, 0 for K, -1 for pre-K'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('students');
    }

}
