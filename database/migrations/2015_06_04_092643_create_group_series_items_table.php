<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupSeriesItemsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_series_items', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('group_series_id');
            $table->integer('post_id')->nullable();
            $table->string('url', 500);
            $table->string('thumbnail_img', 255)->nullable();
            $table->string('title', 500)->nullable();
            $table->tinyInteger('type')->default(0);
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
        Schema::drop('group_series_items');
    }

}
