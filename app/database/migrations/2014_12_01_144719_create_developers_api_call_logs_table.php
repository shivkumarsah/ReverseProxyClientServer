<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevelopersApiCallLogsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('developers_api_call_logs', function(Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->integer('developer_id')->unsigned();
                    $table->foreign('developer_id')->references('developer_id')->on('developers');
                    $table->string('called_api_key');
                    $table->text('api_log_comment');
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
        Schema::drop('developers_api_call_logs');
    }

}
