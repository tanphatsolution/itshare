<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionCategoryTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('question_category')) {
            Schema::create('question_category', function(Blueprint $table) {
                $table->unsignedInteger('question_id');
                $table->unsignedInteger('category_id');
                
                $table->timestamps();

                $table->primary(['question_id', 'category_id']);

                $table->foreign('question_id')->references('id')->on('questions')
                    ->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('category_id')->references('id')->on('categories')
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
        if (Schema::hasTable('question_category')) {
            Schema::drop('question_category');
        }
    }

}
