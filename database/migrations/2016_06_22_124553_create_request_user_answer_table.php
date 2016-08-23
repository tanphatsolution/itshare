<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestUserAnswerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('request_user_answer')) {
            Schema::create('request_user_answer', function(Blueprint $table) {
                
                $table->unsignedInteger('user_id');
                $table->unsignedInteger('question_id');
                $table->boolean('is_answer')->default(0);

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
        if (Schema::hasTable('request_user_answer')) {
            Schema::drop('request_user_answer');
        }
    }
}
