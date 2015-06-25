<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubjectsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subjects', function(Blueprint $table) {
                    $table->tinyInteger('subject_id')->unsigned()->autoIncrement();
                    $table->string('name');
                    $table->timestamps();
                    $table->index('name');
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
        Schema::drop('subjects');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

}
