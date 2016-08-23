<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContestCategoriesTable extends Migration {

    public function up()
    {
        if (!Schema::hasTable('contest_categories')) {
            Schema::create('contest_categories', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('contest_id');
                $table->integer('category_id');
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
        if (Schema::hasTable('contest_categories')) {
            Schema::drop('contest_categories');
        }
    }

}
