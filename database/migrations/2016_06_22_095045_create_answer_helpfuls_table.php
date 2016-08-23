<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswerHelpfulsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('answer_helpfuls')) {
            Schema::create('answer_helpfuls', function(Blueprint $table) {
                $table->unsignedInteger('answer_id');
                $table->unsignedInteger('user_id');
                
                $table->timestamps();

                $table->primary(['answer_id', 'user_id']);

                $table->foreign('answer_id')->references('id')->on('answers')
                    ->onUpdate('cascade')->onDelete('cascade');

                $table->foreign('user_id')->references('id')->on('users')
                    ->onUpdate('cascade')->onDelete('cascade');
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
        if (Schema::hasTable('answer_helpfuls')) {
            Schema::drop('answer_helpfuls');
        }
    }
}
