<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function(Blueprint $table) {
                    $table->increments('user_id');
                    $table->tinyInteger('role_id')->unsigned()->default(1);
                    $table->foreign('role_id')->references('role_id')->on('roles');
                    $table->string('email', 100)->unique();
                    $table->string('username', 100)->unique();
                    $table->string('password');
                    $table->string('confirmation_code');
                    $table->rememberToken()->nullable();
                    $table->boolean('confirmed')->default(false);
                    $table->timestamps();
                });
        // Creates password reminders table
        Schema::create('password_reminders', function ($table) {
                    $table->string('email', 100);
                    $table->string('token');
                    $table->timestamp('created_at');
                });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('password_reminders');
        Schema::drop('users');
    }

}
