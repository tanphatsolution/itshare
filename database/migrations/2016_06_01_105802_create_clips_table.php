<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClipsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('clips')) {
            Schema::create('clips', function(Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('question_id');
                $table->unsignedInteger('user_id');
                
                $table->foreign('question_id')->references('id')->on('questions')
                    ->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')
                    ->onUpdate('cascade')->onDelete('cascade');

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('clips')) {
            Schema::drop('clips');
        }
    }

}
