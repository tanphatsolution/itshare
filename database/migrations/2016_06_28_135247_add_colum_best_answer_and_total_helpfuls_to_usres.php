<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumBestAnswerAndTotalHelpfulsToUsres extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('total_best_answer')->after('blocked')->default(0);
            $table->integer('total_helpful_answer')->after('total_best_answer')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
             $table->dropColumn('total_best_answer');
             $table->dropColumn('total_helpful_answer');
        });
    }
}
