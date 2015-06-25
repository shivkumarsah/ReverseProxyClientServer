<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function(Blueprint $table) {
                    $table->tinyInteger('role_id')->unsigned()->autoIncrement();
                    $table->string('role_name', 100);
                    $table->timestamps();
                    $table->unique('role_name');
                });
        $date = new \DateTime;        
        DB::table('roles')->insert(
                array('role_id' => 1, 'role_name' => 'admin', 'created_at'=>$date, 'updated_at'=>$date)
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('roles');
    }

}
