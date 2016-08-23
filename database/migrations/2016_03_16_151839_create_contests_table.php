<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContestsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('contests')) {
            Schema::create('contests', function(Blueprint $table) {
                $table->increments('id');
                $table->string('title', 50);
                $table->string('domain', 64);
                $table->timestamp('term_start');
                $table->timestamp('term_end');
                $table->integer('user_id');
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
        if (Schema::hasTable('contests')) {
            Schema::drop('contests');
        }
    }
}
