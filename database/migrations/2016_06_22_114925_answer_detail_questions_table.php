<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AnswerDetailQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('answer_detail_questions')) {
            Schema::create('answer_detail_questions', function(Blueprint $table) {
                $table->increments('id');
                $table->text('content')->nullable();
                $table->unsignedInteger('user_id');
                $table->unsignedInteger('request_detail_question_id');

                $table->foreign('request_detail_question_id')->references('id')->on('request_detail_questions')
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
        if (Schema::hasTable('answer_detail_questions')) {
            Schema::drop('answer_detail_questions');
        }
    }
}
