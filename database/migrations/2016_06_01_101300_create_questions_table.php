<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('questions')) {
            Schema::create('questions', function(Blueprint $table) {
                $table->increments('id');
                $table->string('title', 500);
                $table->string('slug', 500);
                $table->text('content')->nullable();
                $table->string('language_code', 5);
                $table->integer('number_clip')->default(0);
                $table->integer('number_view')->default(0);
                $table->integer('number_answer')->default(0);
                $table->integer('first_time_answer')->default(0);
                $table->boolean('blocked')->default(0);
                $table->boolean('solved')->default(0);

                $table->unsignedInteger('user_id');
                $table->foreign('user_id')->references('id')->on('users')
                    ->onUpdate('cascade')->onDelete('cascade');
                
                $table->timestamp('published_at')->nullable()->default(NULL);
                $table->softDeletes();
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
        if (Schema::hasTable('questions')) {
            Schema::drop('questions');
        }
    }

}
