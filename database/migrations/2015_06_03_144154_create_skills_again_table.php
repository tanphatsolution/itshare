<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkillsAgainTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('skills'))
        {
            Schema::create('skills', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('skill_category_id');
                $table->string('name', 50);
                $table->string('short_name', 50);
                $table->timestamps();
                $table->softDeletes();
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
        //
        Schema::drop('skills');
    }

}
