<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockRankingWeklyTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_ranking_weekly', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('post_id');
            $table->integer('stock_count')->default(0);
            $table->string('year');
            $table->string('week');
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
        Schema::drop('stock_ranking_weekly');
    }


}
