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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->string('username', 30);
            $table->string('password', 128);
            $table->string('email', 50);
            $table->string('phone', 15)->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->string('forget_token', 100)->nullable();
            $table->string('active_token', 100)->nullable();
            $table->boolean('blocked')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('users');
    }

}
