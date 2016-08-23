<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->increments('id');
            $table->text('message');
            $table->char('trackable_type', 3);
            $table->integer('user_id')->default(0);
            $table->integer('post_id')->default(0);
            $table->integer('category_id')->default(0);
            $table->integer('group_id')->default(0);
            $table->string('screen_code')->default(NULL);
            $table->string('action_code')->default(NULL);
            $table->integer('position_click')->default(0);
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
        Schema::drop('activities');
    }

}
