<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevelopersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('developers', function(Blueprint $table) {
                    $table->increments('developer_id');
                    $table->string('developer_name', 150);
                    $table->string('api_key');
                    $table->string('associated_schools', 5000)->default('0');
                    $table->softDeletes();
                    $table->timestamps();
                    $table->index('developer_name');
                });
        DB::statement("ALTER TABLE `developers` CHANGE `associated_schools` `associated_schools` VARCHAR(5000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT 'Comma separated permitted schools ids. 0 Means permissions to all schools'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('developers');
    }

}
