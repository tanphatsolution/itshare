<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedbacksTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('feedbacks', function ($table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('username');
            $table->string('title');
            $table->string('message');
            $table->string('name');
            $table->string('email');
            $table->integer('status');
            $table->timestamps();
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
        Schema::drop('feedbacks');
    }

}
