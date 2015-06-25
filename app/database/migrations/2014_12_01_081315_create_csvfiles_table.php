<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCsvfilesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('csvfiles', function(Blueprint $table) {
                    $table->tinyInteger('file_id')->unsigned()->autoIncrement();
                    $table->string('file_name', 100);
                    $table->index('file_name');
                    $table->enum('file_available', array('0', '1', '2', '3'))->default('0');
                    $table->enum('file_is_optional', array('0', '1'))->default('0');
                    $table->enum('status', array('0', '1'))->default('1');
                    $table->tinyInteger('file_order');
                    $table->enum('file_import_status', array('0', '1', '2'))->default('0');
                    $table->longText('file_import_comment')->default('');
                    $table->longText('file_upload_comment')->default('');
                    $table->integer('import_successful_records')->default('0');
                    $table->integer('import_unsuccessful_records')->default('0');
                    $table->timestamp('file_last_import_started_at')->nullable();
                    $table->timestamp('file_last_imported_at')->nullable();
                    $table->nullableTimestamps();
                });
                
             
        DB::statement("ALTER TABLE `csvfiles` CHANGE `file_available` `file_available` ENUM( '0', '1', '2', '3' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '0-> File Missing, 1-> File Available, 2->Import in Progress, 3-> Import Complete'"); 
        
        DB::table('csvfiles')->insert(array('file_id' => 1, 'file_name' => 'Schools.csv', 'file_order' => 1));
        DB::table('csvfiles')->insert(array('file_id' => 2, 'file_name' => 'Students.csv', 'file_order' => 2));
        DB::table('csvfiles')->insert(array('file_id' => 3, 'file_name' => 'Teachers.csv', 'file_order' => 3));
        DB::table('csvfiles')->insert(array('file_id' => 4, 'file_name' => 'Subjects.csv', 'file_order' => 4 , 'file_is_optional' => 1 , 'status' => 0));
        DB::table('csvfiles')->insert(array('file_id' => 5, 'file_name' => 'Courses.csv', 'file_order' => 5));
        DB::table('csvfiles')->insert(array('file_id' => 6, 'file_name' => 'Enrollments.csv', 'file_order' => 6));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::drop('csvfiles');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

}
