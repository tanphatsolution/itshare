<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('answers')) {
            Schema::create('answers', function(Blueprint $table) {
                $table->increments('id');
                $table->text('content')->nullable();
                $table->integer('number_helpful')->default(0);
                $table->boolean('blocked')->default(0);
                $table->boolean('best_answer')->default(0);
                $table->unsignedInteger('user_id');
                $table->unsignedInteger('question_id');
                $table->unsignedInteger('parent')->default(0);

                $table->foreign('user_id')->references('id')->on('users')
                    ->onUpdate('cascade')->onDelete('cascade');

                $table->foreign('question_id')->references('id')->on('questions')
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
        if (Schema::hasTable('answers')) {
            Schema::drop('answers');
        }
    }
}
