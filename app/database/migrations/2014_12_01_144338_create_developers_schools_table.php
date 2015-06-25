<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevelopersSchoolsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('developer_school', function(Blueprint $table)
		{
			$table->integer('developer_id')->unsigned();
                    $table->foreign('developer_id')->references('developer_id')
                            ->on('developers')
                            ->onDelete('cascade');
                    $table->integer('school_id')->unsigned();
                    $table->foreign('school_id')->references('school_id')
                            ->on('schools')
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
		Schema::drop('developer_school');
	}

}
