<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCsvfilesLogsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('csvfiles_logs', function(Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->tinyInteger('file_id')->unsigned();
                    $table->foreign('file_id')->references('file_id')->on('csvfiles');
                    $table->longText('log_comment');
                    $table->integer('log_successful_records')->default('0');
                    $table->integer('log_unsuccessful_records')->default('0');
                    $table->timestamp('log_last_import_started_at');
                    $table->timestamp('log_last_imported_at');
                    $table->timestamp('added_on')->default(DB::raw('CURRENT_TIMESTAMP'));
                });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('csvfiles_logs');
    }

}
