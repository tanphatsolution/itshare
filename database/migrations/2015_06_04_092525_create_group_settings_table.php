<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupSettingsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_settings', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('group_id');
            $table->tinyInteger('privacy_flag')->default(0);
            $table->boolean('add_member_flag')->default(0);
            $table->boolean('add_post_flag')->default(0);
            $table->boolean('approve_post_flag')->default(1);
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
        Schema::drop('group_settings');
    }

}
